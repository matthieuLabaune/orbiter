<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Project;

class HealthScoreService
{
    public function forModule(Module $module): array
    {
        $requirements = $module->requirements()->with('tests')->get();
        $total = $requirements->count();

        if ($total === 0) {
            return ['formalized' => 0, 'covered' => 0, 'verified' => 0, 'validated' => 0, 'total' => 0];
        }

        return [
            'formalized' => $total,
            'covered' => $requirements->filter(fn ($r) => $r->tests->isNotEmpty())->count(),
            'verified' => $requirements->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count(),
            'validated' => $requirements->filter(fn ($r) => $r->vv_status === 'validated')->count(),
            'total' => $total,
        ];
    }

    public function forProject(Project $project): array
    {
        $requirements = $project->requirements()->with('tests')->get();
        $total = $requirements->count();

        if ($total === 0) {
            return ['formalized' => 0, 'covered' => 0, 'verified' => 0, 'validated' => 0, 'total' => 0, 'percentage' => 0];
        }

        $validated = $requirements->filter(fn ($r) => $r->vv_status === 'validated')->count();

        return [
            'formalized' => $total,
            'covered' => $requirements->filter(fn ($r) => $r->tests->isNotEmpty())->count(),
            'verified' => $requirements->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count(),
            'validated' => $validated,
            'total' => $total,
            'percentage' => round(($validated / $total) * 100),
        ];
    }
}
