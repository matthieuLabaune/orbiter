<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);
        $lessons = $project->lessons()->with(['module', 'requirement', 'author'])->latest()->get();
        return view('pages.lessons.index', compact('project', 'lessons'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);
        $modules = $project->modules()->orderBy('name')->get();
        $requirements = $project->requirements()->orderBy('ref')->get();
        return view('pages.lessons.create', compact('project', 'modules', 'requirements'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'module_id' => ['nullable', 'exists:modules,id'],
            'requirement_id' => ['nullable', 'exists:requirements,id'],
            'tags' => ['nullable', 'string'],
        ]);
        Lesson::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'] ?? null,
            'requirement_id' => $validated['requirement_id'] ?? null,
            'tags' => $validated['tags'] ? array_map('trim', explode(',', $validated['tags'])) : [],
            'author_id' => $request->user()->id,
        ]);
        return redirect()->route('projects.lessons.index', $project)->with('success', 'Lesson learned enregistrée.');
    }

    public function show(Project $project, Lesson $lesson)
    {
        Gate::authorize('view', $project);
        $lesson->load(['module', 'requirement', 'author']);
        return view('pages.lessons.show', compact('project', 'lesson'));
    }
}
