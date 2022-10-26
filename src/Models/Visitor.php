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

    protected $dispatchesEvents = [
        'creating' => \Habib\Dashboard\Events\Visitor\VisitorCreatingEvent::class,
        'updating' => \Habib\Dashboard\Events\Visitor\VisitorUpdatingEvent::class,
        'deleting' => \Habib\Dashboard\Events\Visitor\VisitorDeletingEvent::class,
        'created' => \Habib\Dashboard\Events\Visitor\VisitorCreatedEvent::class,
        'updated' => \Habib\Dashboard\Events\Visitor\VisitorUpdatedEvent::class,
        'deleted' => \Habib\Dashboard\Events\Visitor\VisitorDeletedEvent::class,
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
