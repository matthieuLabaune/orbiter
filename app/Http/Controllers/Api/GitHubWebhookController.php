<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\WebhookProcessorService;
use Illuminate\Http\Request;

class GitHubWebhookController extends Controller
{
    public function handle(Request $request, Project $project, WebhookProcessorService $processor)
    {
        // Verify signature if secret is configured
        $secret = config('services.github.webhook_secret');
        if ($secret) {
            $signature = $request->header('X-Hub-Signature-256');
            $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

            if (! hash_equals($expected, $signature ?? '')) {
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $eventType = $request->header('X-GitHub-Event', 'unknown');
        $payload = $request->all();

        $processor->process($project, $eventType, $payload);

        return response()->json(['status' => 'processed', 'event' => $eventType]);
    }
}
