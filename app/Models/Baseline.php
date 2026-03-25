<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Baseline extends Model
{
    protected $fillable = ['project_id', 'ref', 'title', 'description', 'snapshot', 'signed_by', 'is_immutable'];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
            'is_immutable' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
