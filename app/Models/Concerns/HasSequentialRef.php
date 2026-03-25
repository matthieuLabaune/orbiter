<?php

namespace App\Models\Concerns;

trait HasSequentialRef
{
    public static function bootHasSequentialRef(): void
    {
        static::creating(function ($model) {
            if (empty($model->ref)) {
                $prefix = $model->getRefPrefix();
                $projectId = $model->project_id;

                $lastRef = static::where('project_id', $projectId)
                    ->where('ref', 'like', $prefix . '-%')
                    ->orderByRaw("CAST(SUBSTRING(ref FROM '\\d+$') AS INTEGER) DESC")
                    ->value('ref');

                if ($lastRef) {
                    $lastNumber = (int) substr($lastRef, strlen($prefix) + 1);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }

                $model->ref = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    abstract public function getRefPrefix(): string;
}
