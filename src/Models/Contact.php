<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Models\Traits\MediaModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contact extends Model
{
    use MediaModelsTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'details',
    ];

    protected $casts = [
        'details' => JsonCast::class,
    ];

    /**
     * @return MorphTo
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
