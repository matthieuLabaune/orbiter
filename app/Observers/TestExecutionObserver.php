<?php

namespace App\Observers;

use App\Models\TestExecution;
use App\Services\VVStatusService;

class TestExecutionObserver
{
    public function __construct(private VVStatusService $vvService) {}

    public function created(TestExecution $execution): void
    {
        $this->recalculate($execution);
    }

    public function updated(TestExecution $execution): void
    {
        $this->recalculate($execution);
    }

    private function recalculate(TestExecution $execution): void
    {
        $test = $execution->test()->with('requirements')->first();

        if ($test) {
            foreach ($test->requirements as $requirement) {
                $this->vvService->recalculateForRequirement($requirement);
            }
        }
    }
}
