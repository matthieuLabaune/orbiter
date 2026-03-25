<?php

namespace App\Http\Controllers;

use App\Models\Anomaly;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnomalyController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);
        $anomalies = $project->anomalies()->with(['module', 'requirement', 'assignee'])->latest()->get();
        return view('pages.anomalies.index', compact('project', 'anomalies'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);
        $modules = $project->modules()->orderBy('name')->get();
        $requirements = $project->requirements()->orderBy('ref')->get();
        $members = $project->members;
        return view('pages.anomalies.create', compact('project', 'modules', 'requirements', 'members'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:anomaly,non_conformity,defect'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'requirement_id' => ['nullable', 'exists:requirements,id'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);
        $validated['project_id'] = $project->id;

        // Non-conformity must have a requirement
        if ($validated['type'] === 'non_conformity' && empty($validated['requirement_id'])) {
            return back()->withErrors(['requirement_id' => 'Une non-conformité doit être liée à une exigence.'])->withInput();
        }

        $anomaly = Anomaly::create($validated);

        // If non-conformity, set requirement V&V to failed
        if ($anomaly->type === 'non_conformity' && $anomaly->requirement) {
            $anomaly->requirement->update(['vv_status' => 'failed']);
        }

        return redirect()->route('projects.anomalies.index', $project)->with('success', 'Anomalie signalée.');
    }

    public function show(Project $project, Anomaly $anomaly)
    {
        Gate::authorize('view', $project);
        $anomaly->load(['module', 'requirement', 'assignee', 'lesson']);
        return view('pages.anomalies.show', compact('project', 'anomaly'));
    }
}
