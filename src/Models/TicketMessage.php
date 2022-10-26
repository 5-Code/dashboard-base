<?php

namespace Habib\Dashboard\Models;

use Habib\Dashboard\Models\Traits\HasOwner;
use Habib\Dashboard\Models\Traits\MediaModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketMessage extends Model
{
    use HasOwner;
    use MediaModelsTrait;
    use SoftDeletes;

    protected $fillable = [
        'message',
        'owner_id',
        'owner_type',
        'ticket_id',
    ];

    protected $dispatchesEvents = [
        'creating' => \Habib\Dashboard\Events\TicketMessage\TicketMessageCreatingEvent::class,
        'updating' => \Habib\Dashboard\Events\TicketMessage\TicketMessageUpdatingEvent::class,
        'deleting' => \Habib\Dashboard\Events\TicketMessage\TicketMessageDeletingEvent::class,
        'created' => \Habib\Dashboard\Events\TicketMessage\TicketMessageCreatedEvent::class,
        'updated' => \Habib\Dashboard\Events\TicketMessage\TicketMessageUpdatedEvent::class,
        'deleted' => \Habib\Dashboard\Events\TicketMessage\TicketMessageDeletedEvent::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }


}
