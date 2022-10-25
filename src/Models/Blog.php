<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Models\Traits\ContactableTrait;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\HasSlug;
use Habib\Dashboard\Models\Traits\MediaImageTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Blog extends Model
{
    use HasOwner, MediaImageTrait, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'image_id',
        'description',
    ];

    protected $casts = [
        'title' => JsonCast::class,
        'slug' => JsonCast::class,
        'description' => JsonCast::class,
        'status' => 'boolean',
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

}
