<?php

namespace App\Services;

use App\Models\Requirement;

class VVStatusService
{
    public function recalculateForRequirement(Requirement $requirement): void
    {
        $tests = $requirement->tests()->with('executions')->get();

        if ($tests->isEmpty()) {
            $requirement->update(['vv_status' => 'untested']);
            return;
        }

        $allHaveExecutions = $tests->every(fn ($t) => $t->executions->isNotEmpty());

        if (! $allHaveExecutions) {
            $requirement->update(['vv_status' => 'in_test']);
            return;
        }

        $lastResults = $tests->map(fn ($t) => $t->executions->sortByDesc('executed_at')->first()->result);

        if ($lastResults->contains('fail')) {
            $requirement->update(['vv_status' => 'failed']);
            return;
        }

        if ($lastResults->every(fn ($r) => $r === 'pass')) {
            if ($requirement->vv_status !== 'validated') {
                $requirement->update(['vv_status' => 'verified']);
            }
            return;
        }

        $requirement->update(['vv_status' => 'in_test']);
    }
}
