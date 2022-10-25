<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->morphs('owner');
            $table->text('message');
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_messages');
    }
};
