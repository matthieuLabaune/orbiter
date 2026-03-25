<?php

namespace App\Models;

use App\Models\Concerns\HasSequentialRef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasSequentialRef;

    protected $fillable = [
        'project_id', 'ref', 'title', 'description',
        'module_id', 'requirement_id', 'adr_id', 'tags', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function getRefPrefix(): string
    {
        return 'LESSON';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function adr(): BelongsTo
    {
        return $this->belongsTo(Adr::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
