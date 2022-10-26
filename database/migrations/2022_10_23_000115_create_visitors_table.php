<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix() . config('dashboard.visitors.table_name', 'visitors'),
            function (Blueprint $table) {
                $table->id();
                $table->string('ip');
                $table->string('country')->nullable()->index();
                $table->string('operating_system')->nullable()->index();
                $table->string('browser')->nullable()->index();
                $table->string('device')->nullable()->index();
                $table->string('locale')->nullable()->index();
                $table->json('details')->nullable();
                $table->nullableMorphs('owner');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix() . config('dashboard.visitors.table_name', 'visitors'));
    }
};
