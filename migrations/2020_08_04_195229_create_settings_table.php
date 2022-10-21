<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('settings.table_name', 'settings'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50)->default('string');
            $table->text('value')->nullable();
            $table->string('group_by')->nullable();
            $table->string('locale', 2)->default(app()->getLocale());
            $table->unique(['name', 'locale']);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('settings.table_name', 'settings'));
    }
};
