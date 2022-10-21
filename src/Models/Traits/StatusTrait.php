<?php


namespace Habib\Dashboard\Models\Traits;


use Illuminate\Database\Eloquent\Builder;

trait StatusTrait
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where($this->getTable() . '.status', true);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotActive(Builder $builder)
    {
        return $builder->where($this->getTable() . '.status', false);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return !!$this->status;
    }

    /**
     * @return bool
     */
    public function isNotActive(): bool
    {
        return !$this->status;
    }
}
