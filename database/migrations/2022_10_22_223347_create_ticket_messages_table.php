<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Habib\Dashboard\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix() . config('dashboard.ticket_messages.table_name', 'ticket_messages'),
            function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor($new = new Ticket, 'ticket_id')
                    ->constrained($new->getTable())
                    ->cascadeOnDelete();
                $table->morphs('owner');
                $table->text('message');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix() . config('dashboard.ticket_messages.table_name',
                'ticket_messages'));
    }
};
