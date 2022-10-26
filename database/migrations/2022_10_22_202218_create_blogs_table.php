<?php

use Habib\Dashboard\Helpers\Traits\MigrateHelperTrait;
use Habib\Dashboard\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use MigrateHelperTrait;

    public function up()
    {
        Schema::create($this->getTablePrefix() . config('dashboard.blogs.table_name', 'blogs'),
            function (Blueprint $table) {
                $table->id();
                $table->lang('title');
                $table->lang('slug');
                $table->lang('description');
                $table->foreignIdFor(Media::class, 'image_id')->nullable();
                $table->boolean('status')->default(true);
                $table->nullableMorphs('owner');
                $table->softDeletesTz();
                $table->timestampsTz();
            });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTablePrefix() . config('dashboard.blogs.table_name', 'blogs'));
    }
};
