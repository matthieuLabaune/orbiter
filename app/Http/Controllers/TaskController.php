<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request, Project $project)
    {
        Gate::authorize('view', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $members = $project->members;

        $tasks = $project->tasks()
            ->with(['module', 'assignee', 'requirement', 'blockedBy'])
            ->when($request->module, fn ($q, $m) => $q->where('module_id', $m))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->assignee, fn ($q, $a) => $q->where('assignee_id', $a))
            ->orderByRaw("CASE status WHEN 'blocked' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'todo' THEN 3 WHEN 'done' THEN 4 END")
            ->get();

        return view('pages.tasks.index', compact('project', 'tasks', 'modules', 'members'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $members = $project->members;
        $requirements = $project->requirements()->orderBy('ref')->get();
        $allTasks = $project->tasks()->orderBy('title')->get();

        return view('pages.tasks.create', compact('project', 'modules', 'members', 'requirements', 'allTasks'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'requirement_id' => ['nullable', 'exists:requirements,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:todo,in_progress,done,blocked'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'blocked_by' => ['nullable', 'array'],
            'blocked_by.*' => ['exists:tasks,id'],
        ]);

        $task = Task::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'] ?? null,
            'requirement_id' => $validated['requirement_id'] ?? null,
            'assignee_id' => $validated['assignee_id'] ?? null,
            'status' => $validated['status'],
            'progress' => $validated['progress'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        if (! empty($validated['blocked_by'])) {
            $task->blockedBy()->attach($validated['blocked_by']);
        }

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Tâche créée.');
    }

    public function show(Project $project, Task $task)
    {
        Gate::authorize('view', $project);

        $task->load(['module', 'assignee', 'requirement', 'blockedBy', 'blocks']);

        return view('pages.tasks.show', compact('project', 'task'));
    }

    public function edit(Project $project, Task $task)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $members = $project->members;
        $requirements = $project->requirements()->orderBy('ref')->get();
        $allTasks = $project->tasks()->where('id', '!=', $task->id)->orderBy('title')->get();
        $task->load('blockedBy');

        return view('pages.tasks.edit', compact('project', 'task', 'modules', 'members', 'requirements', 'allTasks'));
    }

    public function update(Request $request, Project $project, Task $task)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'requirement_id' => ['nullable', 'exists:requirements,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:todo,in_progress,done,blocked'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'blocked_by' => ['nullable', 'array'],
            'blocked_by.*' => ['exists:tasks,id'],
        ]);

        $task->update($validated);
        $task->blockedBy()->sync($validated['blocked_by'] ?? []);

        return redirect()->route('projects.tasks.show', [$project, $task])->with('success', 'Tâche mise à jour.');
    }

    public function destroy(Project $project, Task $task)
    {
        Gate::authorize('update', $project);

        $task->delete();

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Tâche supprimée.');
    }
}
