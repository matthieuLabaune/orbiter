<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use App\Models\TestExecution;
use Livewire\Component;

class ActivityFeed extends Component
{
    public Project $project;

    public function render()
    {
        // Gather recent activity from multiple sources
        $activities = collect();

        // Recent test executions
        $executions = TestExecution::whereHas('test', fn ($q) => $q->where('project_id', $this->project->id))
            ->with(['test', 'executor'])
            ->latest('executed_at')
            ->limit(10)
            ->get();

        foreach ($executions as $exec) {
            $activities->push([
                'type' => 'test_execution',
                'icon' => match ($exec->result) {
                    'pass' => 'check-circle',
                    'fail' => 'x-circle',
                    default => 'minus-circle',
                },
                'color' => match ($exec->result) {
                    'pass' => 'emerald',
                    'fail' => 'red',
                    default => 'amber',
                },
                'message' => "{$exec->test->ref} → {$exec->result}",
                'detail' => $exec->test->title,
                'user' => $exec->executor?->name,
                'date' => $exec->executed_at,
            ]);
        }

        // Recent requirements (by updated_at)
        $recentReqs = $this->project->requirements()
            ->with('module')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        foreach ($recentReqs as $req) {
            $activities->push([
                'type' => 'requirement',
                'icon' => 'list-checks',
                'color' => 'blue',
                'message' => "{$req->ref} v{$req->version}",
                'detail' => $req->title,
                'user' => null,
                'date' => $req->updated_at,
            ]);
        }

        // Recent ADR
        $recentAdrs = $this->project->adrs()
            ->with('author')
            ->latest()
            ->limit(3)
            ->get();

        foreach ($recentAdrs as $adr) {
            $activities->push([
                'type' => 'adr',
                'icon' => 'file-text',
                'color' => 'purple',
                'message' => "{$adr->ref} — {$adr->status}",
                'detail' => $adr->title,
                'user' => $adr->author?->name,
                'date' => $adr->created_at,
            ]);
        }

        $activities = $activities->sortByDesc('date')->take(15);

        return view('livewire.dashboard.activity-feed', ['activities' => $activities]);
    }
}
