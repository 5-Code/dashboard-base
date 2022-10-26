<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Casts\SlugCast;
use Habib\Dashboard\Helpers\Slugger;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(static function (self $model) {
            if ($model->slug) {
                $model->sluggerByLocals($model->toArray());
            }
        });
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $slugKey
     * @return $this
     */
    public function sluggerByLocals(array $data, string $key = 'name', string $slugKey = 'slug'): static
    {
        $data ??= $this->getAttribute($key) ?? [];
        $slug = [];
        foreach (locals() as $local) {
            $slug[$local] = Slugger::new()->slug($this, "{$slugKey}->{$local}", $data[$key][$local]);
        }
        $this->setAttribute($slugKey, $slug);
        return $this;
    }

    public function initializeHasSlug(): void
    {
        if (!$this->hasCast('slug')) {
            $this->mergeCasts([
                'slug' => SlugCast::class
            ]);
        }
        if (!$this->isFillable('slug')) {
            $this->mergeFillable(['slug']);
        }
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $slugKey
     * @return $this
     */
    public function slugger(array $data, string $key = 'name', string $slugKey = 'slug'): static
    {
        $data ??= $this->getAttributes()[$key] ?? [];

        $slug = Slugger::new()->slug($this, $slugKey, $data[$key]);

        $this->setAttribute($slugKey, $slug);
        return $this;
    }
}
