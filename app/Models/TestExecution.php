<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestExecution extends Model
{
    protected $fillable = ['test_id', 'result', 'executed_by', 'executed_at', 'notes', 'commit_sha', 'duration_ms'];

    protected function casts(): array
    {
        return [
            'executed_at' => 'datetime',
        ];
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }
}
