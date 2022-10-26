<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix() . config('dashboard.tickets.table_name', 'tickets'),
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->lang('slug');
                $table->string('status');
                $table->string('priority')->default('low');
                $table->text('description');
                $table->jsonb('details')->nullable();
                $table->nullableMorphs('owner');
                $table->nullableMorphs('assignee');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix() . config('dashboard.tickets.table_name', 'tickets'));
    }
};
