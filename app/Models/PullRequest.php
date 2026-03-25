<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PullRequest extends Model
{
    protected $fillable = ['project_id', 'github_pr_number', 'title', 'body', 'status', 'author', 'merged_at'];

    protected function casts(): array
    {
        return [
            'merged_at' => 'datetime',
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
