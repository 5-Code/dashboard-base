<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Events\TicketMessage\TicketMessageCreatedEvent;
use Habib\Dashboard\Events\TicketMessage\TicketMessageCreatingEvent;
use Habib\Dashboard\Events\TicketMessage\TicketMessageDeletedEvent;
use Habib\Dashboard\Events\TicketMessage\TicketMessageDeletingEvent;
use Habib\Dashboard\Events\TicketMessage\TicketMessageUpdatedEvent;
use Habib\Dashboard\Events\TicketMessage\TicketMessageUpdatingEvent;
use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\MediaModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TicketMessage extends Model implements HasMedia
{
    use HasOwner;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $fillable = [
        'message',
        'owner_id',
        'owner_type',
        'ticket_id',
    ];

    protected $dispatchesEvents = [
        'creating' => TicketMessageCreatingEvent::class,
        'updating' => TicketMessageUpdatingEvent::class,
        'deleting' => TicketMessageDeletingEvent::class,
        'created' => TicketMessageCreatedEvent::class,
        'updated' => TicketMessageUpdatedEvent::class,
        'deleted' => TicketMessageDeletedEvent::class,
    ];

    /**
     * @return MorphTo
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }


}
