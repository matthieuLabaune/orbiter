<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Test;
use App\Models\TestExecution;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'results' => ['required', 'array'],
            'results.*.test_ref' => ['required', 'string'],
            'results.*.result' => ['required', 'in:pass,fail,skip'],
            'results.*.executed_by' => ['nullable', 'string'],
            'results.*.commit_sha' => ['nullable', 'string'],
            'results.*.duration_ms' => ['nullable', 'integer'],
            'results.*.notes' => ['nullable', 'string'],
        ]);

        $processed = [];

        foreach ($validated['results'] as $result) {
            $test = Test::where('project_id', $project->id)
                ->where('ref', $result['test_ref'])
                ->first();

            if (! $test) {
                $processed[] = ['test_ref' => $result['test_ref'], 'status' => 'not_found'];
                continue;
            }

            TestExecution::create([
                'test_id' => $test->id,
                'result' => $result['result'],
                'executed_at' => now(),
                'notes' => $result['notes'] ?? null,
                'commit_sha' => $result['commit_sha'] ?? null,
                'duration_ms' => $result['duration_ms'] ?? null,
            ]);

            // V&V recalculation handled by Observer
            $processed[] = ['test_ref' => $result['test_ref'], 'status' => 'recorded', 'result' => $result['result']];
        }

        return response()->json(['processed' => $processed]);
    }
}
