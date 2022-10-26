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

    protected $dispatchesEvents = [
        'creating' => \Habib\Dashboard\Events\Contact\ContactCreatingEvent::class,
        'updating' => \Habib\Dashboard\Events\Contact\ContactUpdatingEvent::class,
        'deleting' => \Habib\Dashboard\Events\Contact\ContactDeletingEvent::class,
        'created' => \Habib\Dashboard\Events\Contact\ContactCreatedEvent::class,
        'updated' => \Habib\Dashboard\Events\Contact\ContactUpdatedEvent::class,
        'deleted' => \Habib\Dashboard\Events\Contact\ContactDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
