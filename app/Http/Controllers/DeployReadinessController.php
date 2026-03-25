<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\DeployReadinessService;
use Illuminate\Support\Facades\Gate;

class DeployReadinessController extends Controller
{
    public function index(Project $project, DeployReadinessService $service)
    {
        Gate::authorize('view', $project);

        $currentReadiness = $service->evaluate($project);
        $reviews = $project->deployReadinessReviews()->latest('decided_at')->get();

        return view('pages.deploy-readiness.index', compact('project', 'currentReadiness', 'reviews'));
    }
}
