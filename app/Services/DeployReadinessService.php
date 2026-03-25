<?php

namespace App\Services;

use App\Models\Project;

class DeployReadinessService
{
    public function evaluate(Project $project, int $p0Threshold = 100, int $globalThreshold = 80): array
    {
        $modules = $project->modules()
            ->with(['requirements' => fn ($q) => $q->with('tests')])
            ->get();

        $moduleStatuses = [];
        $blockingItems = [];
        $allGo = true;

        foreach ($modules as $module) {
            $reqs = $module->requirements;
            $total = $reqs->count();

            if ($total === 0) {
                $moduleStatuses[$module->name] = ['status' => 'go', 'reason' => 'No requirements'];
                continue;
            }

            $verified = $reqs->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count();
            $failed = $reqs->where('vv_status', 'failed');
            $p0Untested = $reqs->where('priority', 'P0')->whereIn('vv_status', ['untested', 'in_test']);
            $pct = round(($verified / $total) * 100);

            $isGo = $pct >= $globalThreshold && $failed->isEmpty() && $p0Untested->isEmpty();

            $reasons = [];
            if ($failed->isNotEmpty()) {
                foreach ($failed as $r) {
                    $blockingItems[] = ['type' => 'test_failure', 'ref' => $r->ref, 'reason' => "{$r->ref} has failed tests"];
                    $reasons[] = "{$r->ref} failed";
                }
            }
            if ($p0Untested->isNotEmpty()) {
                foreach ($p0Untested as $r) {
                    $blockingItems[] = ['type' => 'untested_p0', 'ref' => $r->ref, 'reason' => "P0 {$r->ref} not verified"];
                    $reasons[] = "P0 {$r->ref} untested";
                }
            }
            if ($pct < $globalThreshold) {
                $reasons[] = "Only {$pct}% verified (threshold: {$globalThreshold}%)";
            }

            $moduleStatuses[$module->name] = [
                'status' => $isGo ? 'go' : 'no_go',
                'reason' => $isGo ? "{$pct}% REQ verified" : implode(', ', $reasons),
                'verified_pct' => $pct,
            ];

            if (! $isGo) {
                $allGo = false;
            }
        }

        return [
            'result' => $allGo ? 'go' : 'no_go',
            'modules' => $moduleStatuses,
            'blocking_items' => $blockingItems,
        ];
    }
}
