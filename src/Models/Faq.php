<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Events\Faq\FaqCreatedEvent;
use Habib\Dashboard\Events\Faq\FaqCreatingEvent;
use Habib\Dashboard\Events\Faq\FaqDeletedEvent;
use Habib\Dashboard\Events\Faq\FaqDeletingEvent;
use Habib\Dashboard\Events\Faq\FaqUpdatedEvent;
use Habib\Dashboard\Events\Faq\FaqUpdatingEvent;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\HasSlug;
use Habib\Dashboard\Models\MainModel as  Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Faq extends Model
{
    use HasOwner;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'status',
        'description',
    ];

    protected $casts = [
        'title' => JsonCast::class,
        'slug' => JsonCast::class,
        'description' => JsonCast::class,
        'status' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'creating' => FaqCreatingEvent::class,
        'updating' => FaqUpdatingEvent::class,
        'deleting' => FaqDeletingEvent::class,
        'created' => FaqCreatedEvent::class,
        'updated' => FaqUpdatedEvent::class,
        'deleted' => FaqDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
