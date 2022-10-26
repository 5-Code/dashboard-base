<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Events\Visitor\VisitorCreatedEvent;
use Habib\Dashboard\Events\Visitor\VisitorCreatingEvent;
use Habib\Dashboard\Events\Visitor\VisitorDeletedEvent;
use Habib\Dashboard\Events\Visitor\VisitorDeletingEvent;
use Habib\Dashboard\Events\Visitor\VisitorUpdatedEvent;
use Habib\Dashboard\Events\Visitor\VisitorUpdatingEvent;
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
        'user_id' => 'integer',
        'user_type' => 'string',
    ];

    protected $dispatchesEvents = [
        'creating' => VisitorCreatingEvent::class,
        'updating' => VisitorUpdatingEvent::class,
        'deleting' => VisitorDeletingEvent::class,
        'created' => VisitorCreatedEvent::class,
        'updated' => VisitorUpdatedEvent::class,
        'deleted' => VisitorDeletedEvent::class,
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
