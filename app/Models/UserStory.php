<?php

namespace App\Models;

use App\Models\Concerns\HasSequentialRef;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStory extends Model
{
    use HasSequentialRef;

    protected $fillable = [
        'project_id', 'module_id', 'ref', 'title', 'description',
        'acceptance_criteria', 'priority', 'status', 'assignee_id',
    ];

    public function getRefPrefix(): string
    {
        return 'US';
    }

    /**
     * Advancement = % of requirements validated
     */
    protected function advancement(): Attribute
    {
        return Attribute::make(
            get: function () {
                $reqs = $this->requirements;
                $total = $reqs->count();

                if ($total === 0) {
                    return ['validated' => 0, 'verified' => 0, 'total' => 0, 'percentage' => 0];
                }

                $validated = $reqs->where('vv_status', 'validated')->count();
                $verified = $reqs->filter(fn ($r) => in_array($r->vv_status, ['verified', 'validated']))->count();

                return [
                    'validated' => $validated,
                    'verified' => $verified,
                    'total' => $total,
                    'percentage' => round(($validated / $total) * 100),
                ];
            }
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
