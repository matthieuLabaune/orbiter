<?php

namespace App\Livewire\Requirements;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class RequirementList extends Component
{
    use WithPagination;

    public Project $project;

    public string $search = '';
    public string $moduleFilter = '';
    public string $statusFilter = '';
    public string $priorityFilter = '';
    public string $sortBy = 'ref';
    public string $sortDir = 'asc';
    public int $perPage = 25;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedModuleFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $modules = $this->project->modules()->orderBy('name')->get();

        $requirements = $this->project->requirements()
            ->with(['module', 'tests'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('ref', 'like', "%{$this->search}%"))
            ->when($this->moduleFilter, fn ($q) => $q->where('module_id', $this->moduleFilter))
            ->when($this->statusFilter, fn ($q) => $q->where('vv_status', $this->statusFilter))
            ->when($this->priorityFilter, fn ($q) => $q->where('priority', $this->priorityFilter))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.requirements.requirement-list', [
            'requirements' => $requirements,
            'modules' => $modules,
        ]);
    }
}
