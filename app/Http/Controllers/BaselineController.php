<?php

namespace App\Http\Controllers;

use App\Models\Baseline;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BaselineController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);
        $baselines = $project->baselines()->latest()->get();
        return view('pages.baselines.index', compact('project', 'baselines'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);
        return view('pages.baselines.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'ref' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'signed_by' => ['nullable', 'string'],
        ]);

        // Build snapshot
        $requirements = $project->requirements()->with('tests')->get();
        $snapshot = [
            'created_at' => now()->toISOString(),
            'requirements_count' => $requirements->count(),
            'requirements_by_status' => $requirements->groupBy('vv_status')->map->count(),
            'requirements_by_priority' => $requirements->groupBy('priority')->map->count(),
            'tests_count' => $project->tests()->count(),
            'test_executions_count' => $project->tests()->withCount('executions')->get()->sum('executions_count'),
            'modules_count' => $project->modules()->count(),
            'adrs_count' => $project->adrs()->count(),
            'tasks_summary' => $project->tasks()->get()->groupBy('status')->map->count(),
            'anomalies_open' => $project->anomalies()->whereIn('status', ['open', 'investigating'])->count(),
            'coverage' => [
                'total' => $requirements->count(),
                'covered' => $requirements->filter(fn ($r) => $r->tests->isNotEmpty())->count(),
                'verified' => $requirements->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count(),
                'validated' => $requirements->where('vv_status', 'validated')->count(),
            ],
        ];

        Baseline::create([
            'project_id' => $project->id,
            'ref' => $validated['ref'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'snapshot' => $snapshot,
            'signed_by' => $validated['signed_by'] ?? null,
            'is_immutable' => true,
        ]);

        return redirect()->route('projects.baselines.index', $project)->with('success', 'Baseline créée.');
    }

    public function show(Project $project, Baseline $baseline)
    {
        Gate::authorize('view', $project);
        return view('pages.baselines.show', compact('project', 'baseline'));
    }
}
