<?php

namespace App\Services;

use App\Models\Requirement;

class ContextBriefService
{
    public function generate(Requirement $requirement): array
    {
        $requirement->load([
            'module.dependencies',
            'tests.executions',
            'lessons',
            'commits',
            'anomalies',
        ]);

        $module = $requirement->module;
        $adrs = $module ? $module->adrs()->with('author')->get() : collect();

        return [
            'requirement' => [
                'ref' => $requirement->ref,
                'title' => $requirement->title,
                'description' => $requirement->description,
                'acceptance_criteria' => $requirement->acceptance_criteria,
                'priority' => $requirement->priority,
                'vv_status' => $requirement->vv_status,
                'version' => $requirement->version,
                'risk_score' => $requirement->risk_score,
                'risk_impact' => $requirement->risk_impact,
                'risk_probability' => $requirement->risk_probability,
                'risk_detectability' => $requirement->risk_detectability,
            ],
            'module' => $module ? [
                'name' => $module->name,
                'description' => $module->description,
                'dependencies' => $module->dependencies->pluck('name')->toArray(),
            ] : null,
            'tests' => $requirement->tests->map(fn ($t) => [
                'ref' => $t->ref,
                'title' => $t->title,
                'type' => $t->type,
                'last_result' => $t->executions->sortByDesc('executed_at')->first()?->result,
            ])->values()->toArray(),
            'adrs' => $adrs->map(fn ($a) => [
                'ref' => $a->ref,
                'title' => $a->title,
                'status' => $a->status,
                'decision' => $a->decision,
            ])->values()->toArray(),
            'lessons' => $requirement->lessons->map(fn ($l) => [
                'ref' => $l->ref,
                'title' => $l->title,
                'description' => $l->description,
                'tags' => $l->tags,
            ])->values()->toArray(),
            'recent_commits' => $requirement->commits->take(5)->map(fn ($c) => [
                'sha' => substr($c->sha, 0, 7),
                'message' => $c->message,
                'author' => $c->author,
                'date' => $c->committed_at?->toISOString(),
            ])->values()->toArray(),
            'anomalies' => $requirement->anomalies->map(fn ($a) => [
                'ref' => $a->ref,
                'title' => $a->title,
                'type' => $a->type,
                'status' => $a->status,
            ])->values()->toArray(),
        ];
    }

    public function toMarkdown(Requirement $requirement): string
    {
        $data = $this->generate($requirement);
        $r = $data['requirement'];

        $md = "# Context Brief — {$r['ref']}: {$r['title']}\n\n";
        $md .= "**Priority:** {$r['priority']} | **V&V:** {$r['vv_status']} | **Risk Score:** " . ($r['risk_score'] ?? 'N/A') . " | **Version:** v{$r['version']}\n\n";

        if ($r['description']) {
            $md .= "## Description\n{$r['description']}\n\n";
        }
        if ($r['acceptance_criteria']) {
            $md .= "## Acceptance Criteria\n{$r['acceptance_criteria']}\n\n";
        }

        if ($data['module']) {
            $md .= "## Module: {$data['module']['name']}\n{$data['module']['description']}\n";
            if (! empty($data['module']['dependencies'])) {
                $md .= "Dependencies: " . implode(', ', $data['module']['dependencies']) . "\n";
            }
            $md .= "\n";
        }

        if (! empty($data['tests'])) {
            $md .= "## Tests\n";
            foreach ($data['tests'] as $t) {
                $result = $t['last_result'] ?? 'not executed';
                $md .= "- **{$t['ref']}** ({$t['type']}): {$t['title']} → {$result}\n";
            }
            $md .= "\n";
        }

        if (! empty($data['adrs'])) {
            $md .= "## Related ADRs\n";
            foreach ($data['adrs'] as $a) {
                $md .= "- **{$a['ref']}** [{$a['status']}]: {$a['title']}\n";
            }
            $md .= "\n";
        }

        if (! empty($data['lessons'])) {
            $md .= "## Lessons Learned\n";
            foreach ($data['lessons'] as $l) {
                $md .= "- **{$l['ref']}**: {$l['title']}\n";
            }
            $md .= "\n";
        }

        if (! empty($data['anomalies'])) {
            $md .= "## Anomalies\n";
            foreach ($data['anomalies'] as $a) {
                $md .= "- **{$a['ref']}** ({$a['type']}, {$a['status']}): {$a['title']}\n";
            }
            $md .= "\n";
        }

        return $md;
    }
}
