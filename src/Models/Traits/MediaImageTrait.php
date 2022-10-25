<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait MediaImageTrait
{
    use MediaTraits;

    public static function bootMediaImageTrait(): void
    {
        static::deleting(static function (self $model) {
            $model->image->delete();
        });
    }

    /**
     * @param Media $media
     * @return Media|bool
     */
    public function attachMedia(Media $media): Media|bool
    {
        return $this->image()->save($media);
    }

    /**
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }
}
