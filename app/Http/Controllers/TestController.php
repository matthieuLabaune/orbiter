<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        $tests = $project->tests()
            ->with(['requirements', 'executions' => fn ($q) => $q->latest('executed_at')->limit(1)])
            ->orderBy('ref')
            ->get();

        return view('pages.tests.index', compact('project', 'tests'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        $requirements = $project->requirements()->orderBy('ref')->get();

        return view('pages.tests.create', compact('project', 'requirements'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'procedure' => ['nullable', 'string'],
            'expected_result' => ['nullable', 'string'],
            'type' => ['required', 'in:manual,automated,review'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['exists:requirements,id'],
        ]);

        $test = Test::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'procedure' => $validated['procedure'] ?? null,
            'expected_result' => $validated['expected_result'] ?? null,
            'type' => $validated['type'],
        ]);

        if (! empty($validated['requirements'])) {
            $test->requirements()->attach($validated['requirements']);
        }

        return redirect()->route('projects.tests.index', $project)->with('success', 'Test créé.');
    }

    public function show(Project $project, Test $test)
    {
        Gate::authorize('view', $project);

        $test->load(['requirements', 'executions.executor']);

        return view('pages.tests.show', compact('project', 'test'));
    }

    public function edit(Project $project, Test $test)
    {
        Gate::authorize('update', $project);

        $requirements = $project->requirements()->orderBy('ref')->get();
        $test->load('requirements');

        return view('pages.tests.edit', compact('project', 'test', 'requirements'));
    }

    public function update(Request $request, Project $project, Test $test)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'procedure' => ['nullable', 'string'],
            'expected_result' => ['nullable', 'string'],
            'type' => ['required', 'in:manual,automated,review'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['exists:requirements,id'],
        ]);

        $test->update($validated);
        $test->requirements()->sync($validated['requirements'] ?? []);

        return redirect()->route('projects.tests.show', [$project, $test])->with('success', 'Test mis à jour.');
    }

    public function destroy(Project $project, Test $test)
    {
        Gate::authorize('update', $project);

        $test->delete();

        return redirect()->route('projects.tests.index', $project)->with('success', 'Test supprimé.');
    }
}
