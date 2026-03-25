<?php

namespace App\Models;

use App\Models\Concerns\HasSequentialRef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Adr extends Model
{
    use HasSequentialRef;

    protected $fillable = [
        'project_id', 'ref', 'title', 'context', 'decision',
        'consequences', 'status', 'superseded_by', 'author_id',
    ];

    public function getRefPrefix(): string
    {
        return 'ADR';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class)->withTimestamps();
    }

    public function requirements(): BelongsToMany
    {
        return $this->belongsToMany(Requirement::class)->withTimestamps();
    }
}
