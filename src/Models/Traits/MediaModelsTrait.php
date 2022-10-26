<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MediaModelsTrait
{
    use MediaTraits;

    /**
     * @return void
     */
    public static function bootMediaModelsTrait()
    {
        static::deleting(function ($model) {
            $model->media()->delete();
        });
    }

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * @param Media $media
     * @return Model|bool
     */
    public function attachMedia(Media $media): Model|bool
    {
        return $this->media()->save($media);
    }

}
