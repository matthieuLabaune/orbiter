<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DeployReadinessService;
use App\Services\HealthScoreService;

class HealthReportController extends Controller
{
    public function show(Project $project, HealthScoreService $healthService, DeployReadinessService $readinessService)
    {
        $projectHealth = $healthService->forProject($project);

        $moduleHealth = $project->modules()->with('requirements.tests')->get()->map(fn ($m) => [
            'name' => $m->name,
            'status' => $m->status,
            'health' => $healthService->forModule($m),
        ]);

        $readiness = $readinessService->evaluate($project);

        $alerts = [];
        $untestedP0 = $project->requirements()->where('priority', 'P0')->where('vv_status', 'untested')->count();
        $failedReqs = $project->requirements()->where('vv_status', 'failed')->count();
        $blockedTasks = $project->tasks()->where('status', 'blocked')->count();

        if ($untestedP0 > 0) $alerts[] = "{$untestedP0} P0 requirement(s) untested";
        if ($failedReqs > 0) $alerts[] = "{$failedReqs} requirement(s) with failed tests";
        if ($blockedTasks > 0) $alerts[] = "{$blockedTasks} task(s) blocked";

        return response()->json([
            'project' => $project->name,
            'health' => $projectHealth,
            'modules' => $moduleHealth,
            'deploy_readiness' => $readiness['result'],
            'alerts' => $alerts,
        ]);
    }
}
