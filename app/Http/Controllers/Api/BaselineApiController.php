<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Baseline;
use App\Models\Project;
use Illuminate\Http\Request;

class BaselineApiController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'ref' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'signed_by' => ['nullable', 'string'],
        ]);

        $requirements = $project->requirements()->with('tests')->get();

        $baseline = Baseline::create([
            'project_id' => $project->id,
            'ref' => $validated['ref'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'snapshot' => [
                'created_at' => now()->toISOString(),
                'requirements_count' => $requirements->count(),
                'requirements_by_status' => $requirements->groupBy('vv_status')->map->count(),
                'tests_count' => $project->tests()->count(),
                'modules_count' => $project->modules()->count(),
                'coverage' => [
                    'total' => $requirements->count(),
                    'covered' => $requirements->filter(fn ($r) => $r->tests->isNotEmpty())->count(),
                    'verified' => $requirements->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count(),
                    'validated' => $requirements->where('vv_status', 'validated')->count(),
                ],
            ],
            'signed_by' => $validated['signed_by'] ?? null,
            'is_immutable' => true,
        ]);

        return response()->json($baseline, 201);
    }

    public function diff(Project $project, Baseline $baseline, Baseline $other)
    {
        $snap1 = $baseline->snapshot;
        $snap2 = $other->snapshot;

        return response()->json([
            'from' => ['ref' => $baseline->ref, 'created_at' => $baseline->created_at],
            'to' => ['ref' => $other->ref, 'created_at' => $other->created_at],
            'diff' => [
                'requirements_count' => ($snap2['requirements_count'] ?? 0) - ($snap1['requirements_count'] ?? 0),
                'tests_count' => ($snap2['tests_count'] ?? 0) - ($snap1['tests_count'] ?? 0),
                'modules_count' => ($snap2['modules_count'] ?? 0) - ($snap1['modules_count'] ?? 0),
                'coverage_validated' => ($snap2['coverage']['validated'] ?? 0) - ($snap1['coverage']['validated'] ?? 0),
                'coverage_verified' => ($snap2['coverage']['verified'] ?? 0) - ($snap1['coverage']['verified'] ?? 0),
            ],
        ]);
    }
}
