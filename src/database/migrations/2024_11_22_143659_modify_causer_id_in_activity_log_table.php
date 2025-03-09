<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Change the causer_id column to CHAR(36)
            $table->char('causer_id', 36)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Revert back to the original type, if necessary
            $table->unsignedBigInteger('causer_id')->nullable()->change();
        });
    }
};
