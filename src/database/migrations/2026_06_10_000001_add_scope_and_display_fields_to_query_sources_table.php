<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('query_sources', function (Blueprint $table): void {
            // Eloquent local scope applied to the source's query (e.g. "active" → scopeActive()).
            $table->string('scope')->nullable()->after('status_filter');
            // Extra fields shown in the manual picker / preview, also searched server-side.
            $table->json('display_fields')->nullable()->after('label_field');
        });
    }

    public function down(): void
    {
        Schema::table('query_sources', function (Blueprint $table): void {
            $table->dropColumn(['scope', 'display_fields']);
        });
    }
};
