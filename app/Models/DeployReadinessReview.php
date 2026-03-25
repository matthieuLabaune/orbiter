<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeployReadinessReview extends Model
{
    protected $fillable = [
        'project_id', 'ref', 'target_version', 'result',
        'decided_by', 'decided_at', 'module_statuses',
        'blocking_items', 'override_reason',
    ];

    protected function casts(): array
    {
        return [
            'module_statuses' => 'array',
            'blocking_items' => 'array',
            'decided_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
