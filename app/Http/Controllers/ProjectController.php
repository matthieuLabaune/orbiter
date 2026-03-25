<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()->projects()->withCount('modules', 'requirements')->latest()->get();

        return view('pages.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('pages.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create($validated);
        $project->members()->attach($request->user()->id, ['role' => 'owner']);

        return redirect()->route('projects.show', $project)->with('success', 'Projet créé.');
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);

        $project->load(['modules.requirements', 'members']);

        $moduleHealth = $project->modules->map(function ($module) {
            $reqs = $module->requirements;
            $total = $reqs->count();

            return [
                'module' => $module,
                'health' => [
                    'formalized' => $total,
                    'covered' => $reqs->filter(fn ($r) => $r->tests()->exists())->count(),
                    'verified' => $reqs->whereIn('vv_status', ['verified', 'validated'])->count(),
                    'validated' => $reqs->where('vv_status', 'validated')->count(),
                    'total' => $total,
                ],
            ];
        });

        return view('pages.projects.show', compact('project', 'moduleHealth'));
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        return view('pages.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Projet mis à jour.');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé.');
    }
}
