<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $project = $request->route('project');

        if ($project instanceof Project) {
            $isMember = $project->members()->where('user_id', $request->user()->id)->exists();

            if (! $isMember) {
                abort(403, 'You are not a member of this project.');
            }
        }

        return $next($request);
    }
}
