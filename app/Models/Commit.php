<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commit extends Model
{
    protected $fillable = ['project_id', 'sha', 'message', 'author', 'committed_at', 'branch', 'files_changed'];

    protected function casts(): array
    {
        return [
            'committed_at' => 'datetime',
            'files_changed' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requirements(): BelongsToMany
    {
        return $this->belongsToMany(Requirement::class)->withTimestamps();
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class)->withTimestamps();
    }
}
