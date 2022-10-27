<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix().config('dashboard.contacts.table_name', 'contacts'),
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('subject')->nullable();
                $table->text('message');
                $table->jsonb('details')->nullable();
                $table->nullableMorphs('contactable');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix().config('dashboard.contacts.table_name', 'contacts'));
    }
};
