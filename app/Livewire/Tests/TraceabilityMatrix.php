<?php

namespace App\Livewire\Tests;

use App\Models\Project;
use Livewire\Component;

class TraceabilityMatrix extends Component
{
    public Project $project;
    public string $moduleFilter = '';

    public function render()
    {
        $requirements = $this->project->requirements()
            ->with(['module', 'tests.executions'])
            ->when($this->moduleFilter, fn ($q) => $q->where('module_id', $this->moduleFilter))
            ->orderBy('ref')
            ->get();

        $tests = $this->project->tests()
            ->with(['requirements', 'executions' => fn ($q) => $q->latest('executed_at')->limit(1)])
            ->orderBy('ref')
            ->get();

        $modules = $this->project->modules()->orderBy('name')->get();

        // Build matrix: for each requirement, which tests cover it and their last result
        $matrix = $requirements->map(function ($req) use ($tests) {
            $row = ['requirement' => $req, 'cells' => []];
            foreach ($tests as $test) {
                $isLinked = $req->tests->contains($test->id);
                $lastExec = $isLinked ? $test->executions->first() : null;
                $row['cells'][] = [
                    'test' => $test,
                    'linked' => $isLinked,
                    'result' => $lastExec?->result,
                ];
            }
            return $row;
        });

        // Coverage stats
        $totalReqs = $requirements->count();
        $coveredReqs = $requirements->filter(fn ($r) => $r->tests->isNotEmpty())->count();

        return view('livewire.tests.traceability-matrix', [
            'matrix' => $matrix,
            'tests' => $tests,
            'modules' => $modules,
            'totalReqs' => $totalReqs,
            'coveredReqs' => $coveredReqs,
        ]);
    }
}
