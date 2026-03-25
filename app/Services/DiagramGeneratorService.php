<?php

namespace App\Services;

use App\Models\Project;

class DiagramGeneratorService
{
    public function generateArchitecture(Project $project): string
    {
        $modules = $project->modules()->with('dependencies')->get();

        if ($modules->isEmpty()) {
            return "graph TB\n    empty[\"Aucun module\"]";
        }

        $lines = ['graph TB'];

        // Module nodes
        foreach ($modules as $module) {
            $id = $this->sanitizeId($module->name);
            $reqCount = $module->requirements()->count();
            $status = match ($module->status) {
                'active' => ':::active',
                'draft' => ':::draft',
                'deprecated' => ':::deprecated',
                default => '',
            };
            $lines[] = "    {$id}[\"{$module->name}<br/><small>{$reqCount} REQ</small>\"]{$status}";
        }

        // Dependency edges
        foreach ($modules as $module) {
            $fromId = $this->sanitizeId($module->name);
            foreach ($module->dependencies as $dep) {
                $toId = $this->sanitizeId($dep->name);
                $lines[] = "    {$toId} --> {$fromId}";
            }
        }

        // Styles
        $lines[] = '';
        $lines[] = '    classDef active fill:#065f46,stroke:#10b981,color:#d1fae5';
        $lines[] = '    classDef draft fill:#1e293b,stroke:#475569,color:#94a3b8';
        $lines[] = '    classDef deprecated fill:#451a03,stroke:#f59e0b,color:#fde68a';

        return implode("\n", $lines);
    }

    private function sanitizeId(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '_', $name);
    }
}
