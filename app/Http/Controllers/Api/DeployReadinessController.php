<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DeployReadinessService;

class DeployReadinessController extends Controller
{
    public function show(Project $project, DeployReadinessService $service)
    {
        return response()->json($service->evaluate($project));
    }
}
