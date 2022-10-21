<?php

namespace Habib\Dashboard\Models\Traits;

use App\Casts\SlugCast;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(static function (self $model) {
            $model->slug ??= null;
        });
    }

    public function initialize(): void
    {
        if (!$this->has('slug')) {
            $this->mergeCasts([
                'slug' => SlugCast::class
            ]);
        }
        if (!$this->fillable('slug')) {
            $this->mergeFillable(['slug']);
        }
    }
}
