<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequirementVersion extends Model
{
    protected $fillable = [
        'requirement_id', 'title', 'description', 'acceptance_criteria',
        'priority', 'vv_status', 'version', 'changed_by', 'change_reason',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
