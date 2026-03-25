<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use App\Services\HealthScoreService;
use Livewire\Component;

class HealthWidget extends Component
{
    public Project $project;

    public function render(HealthScoreService $service)
    {
        $projectHealth = $service->forProject($this->project);

        $moduleHealth = $this->project->modules()
            ->with('requirements.tests')
            ->get()
            ->map(fn ($module) => [
                'module' => $module,
                'health' => $service->forModule($module),
            ]);

        return view('livewire.dashboard.health-widget', [
            'projectHealth' => $projectHealth,
            'moduleHealth' => $moduleHealth,
        ]);
    }
}
