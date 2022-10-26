<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix() . config('dashboard.media.table_name', 'media'),
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('file_name');
                $table->string('mime_type');
                $table->string('path');
                $table->boolean('visibility')->default(true);
                $table->string('disk')->default('local');
                $table->string('file_hash', 64)->unique();
                $table->string('collection')->nullable();
                $table->jsonb('options')->nullable();
                $table->unsignedBigInteger('size');
                $table->nullableMorphs('model');
                $table->nullableMorphs('owner');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix() . config('dashboard.media.table_name', 'media'));
    }
};
