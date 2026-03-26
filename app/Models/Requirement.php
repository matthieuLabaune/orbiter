<?php

namespace App\Models;

use App\Models\Concerns\HasSequentialRef;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    use HasSequentialRef;

    protected $fillable = [
        'project_id', 'module_id', 'user_story_id', 'ref', 'title', 'description',
        'acceptance_criteria', 'priority', 'vv_status', 'version',
        'risk_impact', 'risk_probability', 'risk_detectability',
    ];

    public function getRefPrefix(): string
    {
        return 'REQ';
    }

    protected function riskScore(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (is_null($this->risk_impact) || is_null($this->risk_probability) || is_null($this->risk_detectability)) {
                    return null;
                }
                return $this->risk_impact * $this->risk_probability * (6 - $this->risk_detectability);
            }
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function userStory(): BelongsTo
    {
        return $this->belongsTo(UserStory::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class)->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(RequirementVersion::class);
    }

    public function commits(): BelongsToMany
    {
        return $this->belongsToMany(Commit::class)->withTimestamps();
    }

    public function pullRequests(): BelongsToMany
    {
        return $this->belongsToMany(PullRequest::class)->withTimestamps();
    }

    public function adrs(): BelongsToMany
    {
        return $this->belongsToMany(Adr::class)->withTimestamps();
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function anomalies(): HasMany
    {
        return $this->hasMany(Anomaly::class);
    }
}
