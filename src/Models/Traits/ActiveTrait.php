<?php


namespace Habib\Dashboard\Models\Traits;


use Illuminate\Database\Eloquent\Builder;

trait ActiveTrait
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where($this->getTable() . '.active', true);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotActive(Builder $builder)
    {
        return $builder->where($this->getTable() . '.active', false);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return !!$this->active;
    }

    /**
     * @return bool
     */
    public function isNotActive(): bool
    {
        return !$this->active;
    }
}
