<?php

namespace App\Livewire\Planning;

use App\Models\Project;
use Livewire\Component;

class GanttChart extends Component
{
    public Project $project;

    public function render()
    {
        $tasks = $this->project->tasks()
            ->with(['module', 'blockedBy'])
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->orderBy('start_date')
            ->get();

        // Format for frappe-gantt
        $ganttTasks = $tasks->map(fn ($task) => [
            'id' => (string) $task->id,
            'name' => $task->title,
            'start' => $task->start_date->format('Y-m-d'),
            'end' => $task->end_date->format('Y-m-d'),
            'progress' => $task->progress,
            'dependencies' => $task->blockedBy->pluck('id')->map(fn ($id) => (string) $id)->join(', '),
            'custom_class' => match ($task->status) {
                'done' => 'bar-done',
                'in_progress' => 'bar-progress',
                'blocked' => 'bar-blocked',
                default => 'bar-todo',
            },
        ])->values();

        $modules = $tasks->groupBy(fn ($t) => $t->module?->name ?? 'Sans module');

        return view('livewire.planning.gantt-chart', [
            'ganttTasks' => $ganttTasks,
            'modules' => $modules,
        ]);
    }
}
