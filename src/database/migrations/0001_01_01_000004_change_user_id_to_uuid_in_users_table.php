<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ---------------------------------------------------------
        // TEIL 1: USERS TABELLE (Integer ID -> UUID)
        // ---------------------------------------------------------

        // 1. Neue UUID Spalte anlegen
        if (!Schema::hasColumn('users', 'new_id')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->uuid('new_id')->after('id')->nullable();
            });
        }

        // 2. Daten generieren (falls es schon User gibt)
        DB::table('users')->whereNull('new_id')->orderBy('id')->chunk(200, function ($users) {
            foreach ($users as $user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['new_id' => (string) Str::uuid()]);
            }
        });

        // 3. Alte ID löschen
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'id')) {
                $table->dropColumn('id');
            }
        });

        // 4. Neue Spalte umbenennen und Primary Key setzen
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'new_id')) {
                $table->renameColumn('new_id', 'id');
            }
        });

        Schema::table('users', function (Blueprint $table): void {
             // Sicherstellen, dass es Primary Key ist
             $table->uuid('id')->nullable(false)->change();
             $table->primary('id');
        });


        // ---------------------------------------------------------
        // TEIL 2: SESSIONS TABELLE (user_id anpassen)
        // ---------------------------------------------------------

        // WICHTIG: Wir prüfen manuell, ob der Foreign Key existiert, 
        // bevor wir Schema::table aufrufen. Das verhindert den Absturz.
        
        $fkExists = false;
        // Prüfung nur für MySQL/MariaDB (dein aktueller Treiber)
        if (DB::getDriverName() !== 'sqlite') {
            $check = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'sessions' 
                AND CONSTRAINT_NAME = 'sessions_user_id_foreign'
            ");
            $fkExists = !empty($check);
        }

        // Wenn FK existiert, löschen wir ihn
        if ($fkExists) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        // Spalte löschen und neu anlegen
        Schema::table('sessions', function (Blueprint $table) {
            if (Schema::hasColumn('sessions', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table): void {
            $table->dropColumn('user_id');
            $table->foreignId('user_id')->nullable()->index();
        });
    }
};