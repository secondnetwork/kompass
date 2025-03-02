<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Zuerst die bestehende Tabelle ändern, um die UUID-Spalte hinzuzufügen
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('new_id')->after('id'); // Neue UUID-Spalte hinzufügen
        });

        // Bestehende Daten in die neue UUID-Spalte kopieren
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update(['new_id' => (string) Str::uuid()]);
        }

        // Alte ID-Spalte löschen
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id'); // Alte ID-Spalte löschen
            $table->renameColumn('new_id', 'id'); // Neue UUID-Spalte umbenennen
        });

        Schema::table('sessions', function (Blueprint $table) {
            // Überprüfen, ob die user_id-Spalte existiert, bevor du versuchst, den Fremdschlüssel zu löschen
            if (Schema::hasColumn('sessions', 'user_id')) {
                // Überprüfen, ob der Fremdschlüssel existiert, bevor du versuchst, ihn zu löschen
                $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'sessions' AND COLUMN_NAME = 'user_id'");
                if (!empty($foreignKeys)) {
                    $table->dropForeign(['user_id']); // Fremdschlüssel löschen
                }
            }
            $table->dropColumn('user_id'); // Alte user_id-Spalte löschen

            // Neue user_id-Spalte als UUID hinzufügen
            $table->uuid('user_id')->nullable()->index(); // UUID für die user_id
        });
    }
};
