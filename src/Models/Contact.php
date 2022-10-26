<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Events\Contact\ContactCreatedEvent;
use Habib\Dashboard\Events\Contact\ContactCreatingEvent;
use Habib\Dashboard\Events\Contact\ContactDeletedEvent;
use Habib\Dashboard\Events\Contact\ContactDeletingEvent;
use Habib\Dashboard\Events\Contact\ContactUpdatedEvent;
use Habib\Dashboard\Events\Contact\ContactUpdatingEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contact extends Model implements HasMedia
{

    use InteractsWithMedia;

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

    protected $dispatchesEvents = [
        'creating' => ContactCreatingEvent::class,
        'updating' => ContactUpdatingEvent::class,
        'deleting' => ContactDeletingEvent::class,
        'created' => ContactCreatedEvent::class,
        'updated' => ContactUpdatedEvent::class,
        'deleted' => ContactDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
