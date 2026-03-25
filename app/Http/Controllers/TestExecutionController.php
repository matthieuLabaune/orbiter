<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Test;
use App\Models\TestExecution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestExecutionController extends Controller
{
    public function store(Request $request, Project $project, Test $test)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'result' => ['required', 'in:pass,fail,skip'],
            'notes' => ['nullable', 'string'],
        ]);

        TestExecution::create([
            'test_id' => $test->id,
            'result' => $validated['result'],
            'executed_by' => $request->user()->id,
            'executed_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // V&V recalculation handled by TestExecutionObserver

        return redirect()->route('projects.tests.show', [$project, $test])
            ->with('success', 'Exécution enregistrée.');
    }
}
