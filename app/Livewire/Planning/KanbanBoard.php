<?php

namespace App\Livewire\Planning;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;

class KanbanBoard extends Component
{
    public Project $project;
    public string $moduleFilter = '';

    public function updateTaskStatus(int $taskId, string $newStatus): void
    {
        $task = Task::where('project_id', $this->project->id)->findOrFail($taskId);

        $task->update([
            'status' => $newStatus,
            'progress' => $newStatus === 'done' ? 100 : ($newStatus === 'todo' ? 0 : $task->progress),
        ]);
    }

    public function render()
    {
        $columns = [
            'todo' => ['label' => 'A faire', 'color' => 'slate'],
            'in_progress' => ['label' => 'En cours', 'color' => 'blue'],
            'blocked' => ['label' => 'Bloqué', 'color' => 'red'],
            'done' => ['label' => 'Terminé', 'color' => 'emerald'],
        ];

        $tasks = $this->project->tasks()
            ->with(['module', 'assignee', 'blockedBy'])
            ->when($this->moduleFilter, fn ($q) => $q->where('module_id', $this->moduleFilter))
            ->get()
            ->groupBy('status');

        $modules = $this->project->modules()->orderBy('name')->get();

        return view('livewire.planning.kanban-board', [
            'columns' => $columns,
            'tasks' => $tasks,
            'modules' => $modules,
        ]);
    }
}
