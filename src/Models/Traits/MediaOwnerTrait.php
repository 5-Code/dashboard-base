<?php

namespace Habib\dashboard\src\Models\Traits;

use Habib\Dashboard\Models\Media;

trait MediaModelsTrait
{
    public function models(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }
}
