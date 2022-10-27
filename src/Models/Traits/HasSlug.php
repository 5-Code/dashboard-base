<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Casts\SlugCast;
use Habib\Dashboard\Helpers\Slugger;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(static function (self $model) {
            if (! $model->hasAttributeMutator($model->getSlugName())) {
                $model->sluggerByLocals($model->toArray());
            }
        });
    }

    /**
     * @return string
     */
    public function getSlugName(): string
    {
        return 'slug';
    }

    /**
     * @param  array|null  $data
     * @param  string|null  $key
     * @param  string|null  $slugName
     * @return $this
     */
    public function sluggerByLocals(array $data = null, ?string $key = null, ?string $slugName = null): static
    {
        $key ??= $this->getSlugKey();
        $slugName ??= $this->getSlugName();
        $data ??= $this->getAttribute($key) ?? [];
        $slug = [];
        foreach (locals() as $local) {
            $slug[$local] = Slugger::new()->slug($this, "{$slugName}->{$local}", $data[$key][$local]);
        }
        $this->setAttribute($slugName, $slug);

        return $this;
    }

    /**
     * @return string
     */
    public function getSlugKey(): string
    {
        return 'name';
    }

    public function initializeHasSlug(): void
    {
        if (! $this->hasCast($this->getSlugName())) {
            $this->mergeCasts([
                $this->getSlugName() => SlugCast::class,
            ]);
        }
        if (! $this->isFillable($this->getSlugName())) {
            $this->mergeFillable([$this->getSlugName()]);
        }
    }

    /**
     * @param  array  $data
     * @param  string  $key
     * @param  string  $slugKey
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
