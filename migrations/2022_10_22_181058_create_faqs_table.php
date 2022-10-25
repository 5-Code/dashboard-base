<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->lang('title');
            $table->lang('slug');
            $table->lang('description');
            $table->boolean('status')->default(true);
            $table->nullableMorphs('owner');
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
};
