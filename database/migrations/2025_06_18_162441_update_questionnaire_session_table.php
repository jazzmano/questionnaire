<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questionnaire_sessions', function (Blueprint $table) {
            // Drop user_id foreign key + column if it exists
            if (Schema::hasColumn('questionnaire_sessions', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            // Step 1: Add nullable UUID column
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Step 2: Backfill UUIDs for existing records
        DB::table('questionnaire_sessions')->whereNull('uuid')->update([
            'uuid' => DB::raw('gen_random_uuid()') // PostgreSQL pgcrypto extension
        ]);

        // Step 3: Alter column to be non-nullable
        Schema::table('questionnaire_sessions', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionnaire_sessions', function (Blueprint $table) {
            // Drop the UUID column
            $table->dropColumn('uuid');

            // Restore user_id column
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

        });
    }
};
