<?php

namespace App\Http\Controllers;

use App\Models\Adr;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdrController extends Controller
{
    public function index(Project $project)
    {
        Gate::authorize('view', $project);

        $adrs = $project->adrs()->with(['author', 'modules'])->orderByDesc('created_at')->get();

        return view('pages.adrs.index', compact('project', 'adrs'));
    }

    public function create(Project $project)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();

        return view('pages.adrs.create', compact('project', 'modules'));
    }

    public function store(Request $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'context' => ['nullable', 'string'],
            'decision' => ['nullable', 'string'],
            'consequences' => ['nullable', 'string'],
            'status' => ['required', 'in:proposed,accepted,deprecated,superseded'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['exists:modules,id'],
        ]);

        $adr = Adr::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'context' => $validated['context'] ?? null,
            'decision' => $validated['decision'] ?? null,
            'consequences' => $validated['consequences'] ?? null,
            'status' => $validated['status'],
            'author_id' => $request->user()->id,
        ]);

        if (! empty($validated['modules'])) {
            $adr->modules()->attach($validated['modules']);
        }

        return redirect()->route('projects.adrs.index', $project)->with('success', 'ADR créé.');
    }

    public function show(Project $project, Adr $adr)
    {
        Gate::authorize('view', $project);

        $adr->load(['author', 'modules', 'requirements']);

        return view('pages.adrs.show', compact('project', 'adr'));
    }

    public function edit(Project $project, Adr $adr)
    {
        Gate::authorize('update', $project);

        $modules = $project->modules()->orderBy('name')->get();
        $adr->load('modules');

        return view('pages.adrs.edit', compact('project', 'adr', 'modules'));
    }

    public function update(Request $request, Project $project, Adr $adr)
    {
        Gate::authorize('update', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'context' => ['nullable', 'string'],
            'decision' => ['nullable', 'string'],
            'consequences' => ['nullable', 'string'],
            'status' => ['required', 'in:proposed,accepted,deprecated,superseded'],
            'superseded_by' => ['nullable', 'string'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['exists:modules,id'],
        ]);

        $adr->update($validated);
        $adr->modules()->sync($validated['modules'] ?? []);

        return redirect()->route('projects.adrs.show', [$project, $adr])->with('success', 'ADR mis à jour.');
    }

    public function destroy(Project $project, Adr $adr)
    {
        Gate::authorize('update', $project);

        $adr->delete();

        return redirect()->route('projects.adrs.index', $project)->with('success', 'ADR supprimé.');
    }
}
