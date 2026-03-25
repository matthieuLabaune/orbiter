<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Requirement;
use App\Services\ContextBriefService;
use Illuminate\Http\Request;

class ContextBriefController extends Controller
{
    public function show(Request $request, Project $project, Requirement $requirement, ContextBriefService $service)
    {
        if ($request->query('format') === 'markdown') {
            return response($service->toMarkdown($requirement), 200, ['Content-Type' => 'text/markdown']);
        }

        return response()->json($service->generate($requirement));
    }
}
