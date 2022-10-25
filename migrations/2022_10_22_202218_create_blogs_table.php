<?php

use Habib\Dashboard\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->lang('title');
            $table->lang('slug');
            $table->lang('description');
            $table->foreignIdFor(Media::class, 'image_id')->nullable();
            $table->boolean('status')->default(true);
            $table->nullableMorphs('owner');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};
