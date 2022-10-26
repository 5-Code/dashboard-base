<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
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
        'creating' => \Habib\Dashboard\Events\Faq\FaqCreatingEvent::class,
        'updating' => \Habib\Dashboard\Events\Faq\FaqUpdatingEvent::class,
        'deleting' => \Habib\Dashboard\Events\Faq\FaqDeletingEvent::class,
        'created' => \Habib\Dashboard\Events\Faq\FaqCreatedEvent::class,
        'updated' => \Habib\Dashboard\Events\Faq\FaqUpdatedEvent::class,
        'deleted' => \Habib\Dashboard\Events\Faq\FaqDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }
}
