<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Events\Blog\BlogCreatedEvent;
use Habib\Dashboard\Events\Blog\BlogCreatingEvent;
use Habib\Dashboard\Events\Blog\BlogDeletedEvent;
use Habib\Dashboard\Events\Blog\BlogDeletingEvent;
use Habib\Dashboard\Events\Blog\BlogUpdatedEvent;
use Habib\Dashboard\Events\Blog\BlogUpdatingEvent;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasOwner, HasSlug;

    use InteractsWithMedia;
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

    protected $dispatchesEvents = [
        'creating' => BlogCreatingEvent::class,
        'updating' => BlogUpdatingEvent::class,
        'deleting' => BlogDeletingEvent::class,
        'created' => BlogCreatedEvent::class,
        'updated' => BlogUpdatedEvent::class,
        'deleted' => BlogDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
