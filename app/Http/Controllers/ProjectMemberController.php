<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        Gate::authorize('manageMembers', $project);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:member,viewer'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        $project->members()->attach($user->id, ['role' => $validated['role']]);

        return back()->with('success', 'Membre ajouté.');
    }

    public function update(Request $request, Project $project, User $user)
    {
        Gate::authorize('manageMembers', $project);

        $validated = $request->validate([
            'role' => ['required', 'in:owner,member,viewer'],
        ]);

        $project->members()->updateExistingPivot($user->id, ['role' => $validated['role']]);

        return back()->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Project $project, User $user)
    {
        Gate::authorize('manageMembers', $project);

        if ($project->members()->wherePivot('role', 'owner')->count() <= 1
            && $project->members()->where('user_id', $user->id)->wherePivot('role', 'owner')->exists()) {
            return back()->with('error', 'Impossible de retirer le dernier owner.');
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Membre retiré.');
    }
}
