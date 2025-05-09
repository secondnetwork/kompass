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
        Schema::create('blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('blockable_id');
            $table->string('blockable_type');
            $table->string('subgroup')->nullable();
            $table->string('name');
            $table->string('type');
            $table->string('status')->nullable();
            $table->text('iconclass')->nullable();
            $table->integer('layoutgrid')->default('0');
            $table->integer('grid')->default('1');
            $table->integer('order')->default('999');

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
        Schema::dropIfExists('blocks');
    }
};
