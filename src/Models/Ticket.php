<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Casts\JsonCast;
use Habib\Dashboard\Events\Ticket\TicketCreatedEvent;
use Habib\Dashboard\Events\Ticket\TicketCreatingEvent;
use Habib\Dashboard\Events\Ticket\TicketDeletedEvent;
use Habib\Dashboard\Events\Ticket\TicketDeletingEvent;
use Habib\Dashboard\Events\Ticket\TicketUpdatedEvent;
use Habib\Dashboard\Events\Ticket\TicketUpdatingEvent;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\HasSlug;
use Habib\Dashboard\Models\Traits\MediaModelsTrait;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasOwner;
    use HasSlug;
    use MediaModelsTrait;

    protected $fillable = [
        'title',
        'description',
        'status',
        'owner_id',
        'owner_type',
        'assignee_id',
        'assignee_type',
        'priority',
        'details',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'status' => 'boolean',
        'owner_id' => 'integer',
        'owner_type' => 'string',
        'details' => JsonCast::class,
    ];

    protected $dispatchesEvents = [
        'creating' => TicketCreatingEvent::class,
        'updating' => TicketUpdatingEvent::class,
        'deleting' => TicketDeletingEvent::class,
        'created' => TicketCreatedEvent::class,
        'updated' => TicketUpdatedEvent::class,
        'deleted' => TicketDeletedEvent::class,
    ];

    public function owner()
    {
        return $this->morphTo();
    }

}
