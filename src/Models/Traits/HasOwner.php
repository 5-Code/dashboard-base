<?php

namespace Habib\Dashboard\Models\Traits;

trait HasOwner
{
    public static function bootHasOwner(): void
    {
        static::creating(static function (self $model) {
            if (auth()->check()) {
                $model->owenr_id ??= auth()->id();
                $model->owner_type ??= auth()->user()?->getMorphClass();
            }
        });
    }

    public function initializeHasOwner(): void
    {
        if (!$this->hasCast('owner_id') || !$this->hasCast('owner_type')) {
            $this->mergeCasts([
                'owner_id' => 'integer',
                'owner_type' => 'string',
            ]);
        }

        if (!$this->isFillable('owner_id') || !$this->isFillable('owner_type')) {
            $this->mergeFillable([
                'owner_id',
                'owner_type',
            ]);
        }
    }
}
