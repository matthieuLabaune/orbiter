<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Requirement;
use App\Models\RequirementVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RequirementController extends Controller
{
    public function index(Request $request, Project $project)
    {
        Gate::authorize('view', $project);

        return view('pages.requirements.index', compact('project'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();

        return view('pages.requirements.create', compact('project', 'modules'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'acceptance_criteria' => ['nullable', 'string'],
            'module_id' => ['required', 'exists:modules,id'],
            'priority' => ['required', 'in:P0,P1,P2,P3'],
            'risk_impact' => ['nullable', 'integer', 'min:1', 'max:5'],
            'risk_probability' => ['nullable', 'integer', 'min:1', 'max:5'],
            'risk_detectability' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $validated['project_id'] = $project->id;

        Requirement::create($validated);

        return redirect()->route('projects.requirements.index', $project)->with('success', 'Exigence créée.');
    }

    public function show(Project $project, Requirement $requirement)
    {
        Gate::authorize('view', $project);

        $requirement->load([
            'module',
            'tests.executions',
            'tasks',
            'versions.changedBy',
            'commits',
            'lessons',
            'anomalies',
        ]);

        return view('pages.requirements.show', compact('project', 'requirement'));
    }

    public function edit(Project $project, Requirement $requirement)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();

        return view('pages.requirements.edit', compact('project', 'requirement', 'modules'));
    }

    public function update(Request $request, Project $project, Requirement $requirement)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'acceptance_criteria' => ['nullable', 'string'],
            'module_id' => ['required', 'exists:modules,id'],
            'priority' => ['required', 'in:P0,P1,P2,P3'],
            'risk_impact' => ['nullable', 'integer', 'min:1', 'max:5'],
            'risk_probability' => ['nullable', 'integer', 'min:1', 'max:5'],
            'risk_detectability' => ['nullable', 'integer', 'min:1', 'max:5'],
            'change_reason' => ['nullable', 'string'],
        ]);

        RequirementVersion::create([
            'requirement_id' => $requirement->id,
            'title' => $requirement->title,
            'description' => $requirement->description,
            'acceptance_criteria' => $requirement->acceptance_criteria,
            'priority' => $requirement->priority,
            'vv_status' => $requirement->vv_status,
            'version' => $requirement->version,
            'changed_by' => $request->user()->id,
            'change_reason' => $validated['change_reason'] ?? null,
        ]);

        unset($validated['change_reason']);
        $validated['version'] = $requirement->version + 1;

        $requirement->update($validated);

        return redirect()->route('projects.requirements.show', [$project, $requirement])
            ->with('success', "Exigence mise à jour (v{$requirement->version}).");
    }

    public function destroy(Project $project, Requirement $requirement)
    {
        Gate::authorize('update', $project);

        $requirement->delete();

        return redirect()->route('projects.requirements.index', $project)->with('success', 'Exigence supprimée.');
    }
}
