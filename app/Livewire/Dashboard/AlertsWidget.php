<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use Livewire\Component;

class AlertsWidget extends Component
{
    public Project $project;

    public function render()
    {
        $alerts = collect();

        // Requirements without tests
        $untestedReqs = $this->project->requirements()
            ->whereDoesntHave('tests')
            ->with('module')
            ->get();

        foreach ($untestedReqs as $req) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'triangle-alert',
                'message' => "{$req->ref} n'a aucun test lié",
                'detail' => $req->title,
                'module' => $req->module?->name,
                'url' => route('projects.requirements.show', [$this->project, $req]),
            ]);
        }

        // Failed tests
        $failedReqs = $this->project->requirements()
            ->where('vv_status', 'failed')
            ->with('module')
            ->get();

        foreach ($failedReqs as $req) {
            $alerts->push([
                'type' => 'error',
                'icon' => 'x-circle',
                'message' => "{$req->ref} a un test en échec",
                'detail' => $req->title,
                'module' => $req->module?->name,
                'url' => route('projects.requirements.show', [$this->project, $req]),
            ]);
        }

        // High risk requirements (score >= 50) that are untested
        $highRiskUntested = $this->project->requirements()
            ->whereNotNull('risk_impact')
            ->whereNotNull('risk_probability')
            ->whereNotNull('risk_detectability')
            ->where('vv_status', 'untested')
            ->with('module')
            ->get()
            ->filter(fn ($r) => $r->risk_score >= 50);

        foreach ($highRiskUntested as $req) {
            $alerts->push([
                'type' => 'critical',
                'icon' => 'flame',
                'message' => "{$req->ref} à haut risque (score {$req->risk_score}) non testé",
                'detail' => $req->title,
                'module' => $req->module?->name,
                'url' => route('projects.requirements.show', [$this->project, $req]),
            ]);
        }

        // Blocked tasks
        $blockedTasks = $this->project->tasks()
            ->where('status', 'blocked')
            ->with('module')
            ->get();

        foreach ($blockedTasks as $task) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'lock',
                'message' => "Tâche bloquée : {$task->title}",
                'detail' => null,
                'module' => $task->module?->name,
                'url' => route('projects.tasks.show', [$this->project, $task]),
            ]);
        }

        // Sort: critical first, then error, then warning
        $priority = ['critical' => 0, 'error' => 1, 'warning' => 2];
        $alerts = $alerts->sortBy(fn ($a) => $priority[$a['type']] ?? 3);

        return view('livewire.dashboard.alerts-widget', ['alerts' => $alerts]);
    }
}
