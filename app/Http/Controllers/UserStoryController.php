<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\UserStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserStoryController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        $stories = $project->userStories()
            ->with(['module', 'assignee', 'requirements'])
            ->latest()
            ->get();

        return view('pages.user-stories.index', compact('project', 'stories'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $members = $project->members;

        return view('pages.user-stories.create', compact('project', 'modules', 'members'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'acceptance_criteria' => ['nullable', 'string'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'priority' => ['required', 'in:P0,P1,P2,P3'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);

        $validated['project_id'] = $project->id;

        UserStory::create($validated);

        return redirect()->route('projects.user-stories.index', $project)->with('success', 'User Story créée.');
    }

    public function show(Project $project, UserStory $userStory)
    {
        Gate::authorize('view', $project);

        $userStory->load(['module', 'assignee', 'requirements.tests', 'requirements.module', 'tasks.assignee']);

        return view('pages.user-stories.show', compact('project', 'userStory'));
    }

    public function edit(Project $project, UserStory $userStory)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $members = $project->members;

        return view('pages.user-stories.edit', compact('project', 'userStory', 'modules', 'members'));
    }

    public function update(Request $request, Project $project, UserStory $userStory)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'acceptance_criteria' => ['nullable', 'string'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'priority' => ['required', 'in:P0,P1,P2,P3'],
            'status' => ['required', 'in:open,in_progress,done,closed'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);

        $userStory->update($validated);

        return redirect()->route('projects.user-stories.show', [$project, $userStory])->with('success', 'User Story mise à jour.');
    }

    public function destroy(Project $project, UserStory $userStory)
    {
        Gate::authorize('update', $project);

        $userStory->delete();

        return redirect()->route('projects.user-stories.index', $project)->with('success', 'User Story supprimée.');
    }
}
