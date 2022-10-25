<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Models\Traits\HasOwner;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasOwner;

    protected $guarded = [];

    protected $casts = [
        'details' => JsonCast::class,
        'ip' => 'string',
        'country' => 'string',
        'operating_system' => 'string',
        'browser' => 'string',
        'device' => 'string',
        'locale' => 'string',
        'user' => 'string',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
