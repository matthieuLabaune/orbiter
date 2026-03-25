<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ModuleController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        $modules = $project->modules()
            ->with('owner')
            ->withCount(['requirements', 'tasks'])
            ->latest()
            ->get();

        return view('pages.modules.index', compact('project', 'modules'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        return view('pages.modules.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,deprecated'],
        ]);

        $validated['project_id'] = $project->id;
        $validated['owner_id'] = $request->user()->id;

        Module::create($validated);

        return redirect()->route('projects.modules.index', $project)->with('success', 'Module créé.');
    }

    public function show(Project $project, Module $module)
    {
        Gate::authorize('view', $project);

        $module->load([
            'requirements.tests',
            'tasks',
            'dependencies',
            'dependents',
            'owner',
        ]);

        return view('pages.modules.show', compact('project', 'module'));
    }

    public function edit(Project $project, Module $module)
    {
        Gate::authorize('update', $project);

        $allModules = $project->modules()->where('id', '!=', $module->id)->get();

        return view('pages.modules.edit', compact('project', 'module', 'allModules'));
    }

    public function update(Request $request, Project $project, Module $module)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,active,deprecated'],
            'dependencies' => ['nullable', 'array'],
        ]);

        $module->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        $module->dependencies()->sync($validated['dependencies'] ?? []);

        return redirect()->route('projects.modules.show', [$project, $module])->with('success', 'Module mis à jour.');
    }

    public function destroy(Project $project, Module $module)
    {
        Gate::authorize('update', $project);

        $module->delete();

        return redirect()->route('projects.modules.index', $project)->with('success', 'Module supprimé.');
    }
}
