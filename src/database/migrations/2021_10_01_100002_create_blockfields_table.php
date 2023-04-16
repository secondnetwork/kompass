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
        Schema::create('blockfields', function (Blueprint $table) {
            $table->id();
            $table->foreignId(('blocktemplate_id'))->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('type')->nullable();
            $table->integer('grid')->default('1');
            $table->integer('order')->default('1');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockfields');
    }
};
