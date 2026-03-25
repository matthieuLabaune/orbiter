<?php

namespace App\Models;

use App\Models\Concerns\HasSequentialRef;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasSequentialRef;

    protected $fillable = ['project_id', 'ref', 'title', 'procedure', 'expected_result', 'type'];

    public function getRefPrefix(): string
    {
        return 'TEST';
    }

    protected function lastResult(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->executions()->latest('executed_at')->value('result')
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requirements(): BelongsToMany
    {
        return $this->belongsToMany(Requirement::class)->withTimestamps();
    }

    public function executions(): HasMany
    {
        return $this->hasMany(TestExecution::class);
    }

    public function commits(): BelongsToMany
    {
        return $this->belongsToMany(Commit::class)->withTimestamps();
    }

    public function pullRequests(): BelongsToMany
    {
        return $this->belongsToMany(PullRequest::class)->withTimestamps();
    }
}
