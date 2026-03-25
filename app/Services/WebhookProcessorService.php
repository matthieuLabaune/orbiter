<?php

namespace App\Services;

use App\Models\Commit;
use App\Models\Project;
use App\Models\PullRequest;
use App\Models\Requirement;
use App\Models\Test;
use App\Models\WebhookEvent;

class WebhookProcessorService
{
    public function process(Project $project, string $eventType, array $payload): void
    {
        // Log the event
        WebhookEvent::create([
            'project_id' => $project->id,
            'event_type' => $eventType,
            'payload' => $payload,
            'processed_at' => now(),
            'status' => 'processed',
        ]);

        match ($eventType) {
            'push' => $this->processPush($project, $payload),
            'pull_request' => $this->processPullRequest($project, $payload),
            default => null,
        };
    }

    private function processPush(Project $project, array $payload): void
    {
        $commits = $payload['commits'] ?? [];
        $branch = str_replace('refs/heads/', '', $payload['ref'] ?? '');

        foreach ($commits as $commitData) {
            $commit = Commit::updateOrCreate(
                ['project_id' => $project->id, 'sha' => $commitData['id']],
                [
                    'message' => $commitData['message'] ?? '',
                    'author' => $commitData['author']['name'] ?? $commitData['author']['username'] ?? null,
                    'committed_at' => $commitData['timestamp'] ?? now(),
                    'branch' => $branch,
                    'files_changed' => array_merge(
                        $commitData['added'] ?? [],
                        $commitData['modified'] ?? [],
                        $commitData['removed'] ?? [],
                    ),
                ]
            );

            // Parse REQ-XXX references
            $reqRefs = $this->extractRefs($commitData['message'] ?? '', 'REQ');
            $reqIds = Requirement::where('project_id', $project->id)
                ->whereIn('ref', $reqRefs)
                ->pluck('id');
            $commit->requirements()->syncWithoutDetaching($reqIds);

            // Parse TEST-XXX references
            $testRefs = $this->extractRefs($commitData['message'] ?? '', 'TEST');
            $testIds = Test::where('project_id', $project->id)
                ->whereIn('ref', $testRefs)
                ->pluck('id');
            $commit->tests()->syncWithoutDetaching($testIds);
        }
    }

    private function processPullRequest(Project $project, array $payload): void
    {
        $prData = $payload['pull_request'] ?? [];
        $action = $payload['action'] ?? '';

        $pr = PullRequest::updateOrCreate(
            ['project_id' => $project->id, 'github_pr_number' => $prData['number'] ?? null],
            [
                'title' => $prData['title'] ?? '',
                'body' => $prData['body'] ?? null,
                'status' => match ($action) {
                    'closed' => ($prData['merged'] ?? false) ? 'merged' : 'closed',
                    default => 'open',
                },
                'author' => $prData['user']['login'] ?? null,
                'merged_at' => ($prData['merged_at'] ?? null) ? now()->parse($prData['merged_at']) : null,
            ]
        );

        // Parse REQ and TEST refs from PR body
        $body = ($prData['title'] ?? '') . "\n" . ($prData['body'] ?? '');

        $reqRefs = $this->extractRefs($body, 'REQ');
        $reqIds = Requirement::where('project_id', $project->id)->whereIn('ref', $reqRefs)->pluck('id');
        $pr->requirements()->syncWithoutDetaching($reqIds);

        $testRefs = $this->extractRefs($body, 'TEST');
        $testIds = Test::where('project_id', $project->id)->whereIn('ref', $testRefs)->pluck('id');
        $pr->tests()->syncWithoutDetaching($testIds);

        // If PR merged, update linked task statuses
        if ($action === 'closed' && ($prData['merged'] ?? false)) {
            foreach ($reqIds as $reqId) {
                $req = Requirement::find($reqId);
                $req?->tasks()->where('status', 'in_progress')->update(['status' => 'done', 'progress' => 100]);
            }
        }
    }

    private function extractRefs(string $text, string $prefix): array
    {
        preg_match_all("/{$prefix}-\d{3}/", $text, $matches);

        return array_unique($matches[0] ?? []);
    }
}
