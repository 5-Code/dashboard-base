<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MediaOwnerTrait
{
    /**
     * @return MorphMany
     */
    public function owners(): MorphMany
    {
        return $this->morphMany(Media::class, 'owner');
    }
}
