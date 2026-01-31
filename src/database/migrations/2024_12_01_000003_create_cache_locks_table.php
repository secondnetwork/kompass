<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cache_locks');
    }
};
