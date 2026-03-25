<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';
    public bool $open = false;

    public function updatedQuery(): void
    {
        $this->open = true;
    }

    public function selectResult(): void
    {
        $this->open = false;
        $this->query = '';
    }

    public function render()
    {
        $results = collect();

        if (strlen($this->query) >= 2) {
            $projectIds = Auth::user()->projects()->pluck('projects.id');
            $q = "%{$this->query}%";

            $results = $results
                ->merge(
                    \App\Models\Requirement::whereIn('project_id', $projectIds)
                        ->where(fn ($query) => $query->where('ref', 'like', $q)->orWhere('title', 'like', $q))
                        ->with('project')
                        ->limit(5)
                        ->get()
                        ->map(fn ($r) => [
                            'type' => 'requirement',
                            'icon' => 'list-checks',
                            'ref' => $r->ref,
                            'title' => $r->title,
                            'project' => $r->project->name,
                            'url' => route('projects.requirements.show', [$r->project, $r]),
                        ])
                )
                ->merge(
                    \App\Models\Test::whereIn('project_id', $projectIds)
                        ->where(fn ($query) => $query->where('ref', 'like', $q)->orWhere('title', 'like', $q))
                        ->with('project')
                        ->limit(5)
                        ->get()
                        ->map(fn ($t) => [
                            'type' => 'test',
                            'icon' => 'test-tubes',
                            'ref' => $t->ref,
                            'title' => $t->title,
                            'project' => $t->project->name,
                            'url' => route('projects.tests.show', [$t->project, $t]),
                        ])
                )
                ->merge(
                    \App\Models\Task::whereIn('project_id', $projectIds)
                        ->where('title', 'like', $q)
                        ->with('project')
                        ->limit(5)
                        ->get()
                        ->map(fn ($t) => [
                            'type' => 'task',
                            'icon' => 'gantt-chart',
                            'ref' => null,
                            'title' => $t->title,
                            'project' => $t->project->name,
                            'url' => route('projects.tasks.show', [$t->project, $t]),
                        ])
                )
                ->merge(
                    \App\Models\Adr::whereIn('project_id', $projectIds)
                        ->where(fn ($query) => $query->where('ref', 'like', $q)->orWhere('title', 'like', $q))
                        ->with('project')
                        ->limit(3)
                        ->get()
                        ->map(fn ($a) => [
                            'type' => 'adr',
                            'icon' => 'file-text',
                            'ref' => $a->ref,
                            'title' => $a->title,
                            'project' => $a->project->name,
                            'url' => route('projects.adrs.show', [$a->project, $a]),
                        ])
                )
                ->merge(
                    \App\Models\Anomaly::whereIn('project_id', $projectIds)
                        ->where(fn ($query) => $query->where('ref', 'like', $q)->orWhere('title', 'like', $q))
                        ->with('project')
                        ->limit(3)
                        ->get()
                        ->map(fn ($a) => [
                            'type' => 'anomaly',
                            'icon' => 'triangle-alert',
                            'ref' => $a->ref,
                            'title' => $a->title,
                            'project' => $a->project->name,
                            'url' => route('projects.anomalies.show', [$a->project, $a]),
                        ])
                );
        }

        return view('livewire.global-search', ['results' => $results]);
    }
}
