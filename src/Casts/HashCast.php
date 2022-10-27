<?php

namespace Habib\Dashboard\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HashCast
 */
class HashCast implements CastsInboundAttributes
{
    /**
     * The hashing algorithm.
     *
     * @var string|null
     */
    protected ?string $algorithm;

    /**
     * Create a new cast class instance.
     *
     * @param  string|null  $algorithm
     * @return void
     */
    public function __construct(?string $algorithm = null)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  string  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return is_null($this->algorithm) ? bcrypt($value) : hash($this->algorithm, $value);
    }
}
