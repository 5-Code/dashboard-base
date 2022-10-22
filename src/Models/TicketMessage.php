<?php

namespace App\Models;

use Habib\Dashboard\Models\Traits\MediaModelsTrait;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use MediaModelsTrait;

    protected $fillable = [
        'message',
        'owner_id',
        'owner_type',
        'ticket_id',
    ];
}
