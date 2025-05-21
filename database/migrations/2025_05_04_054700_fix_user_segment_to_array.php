<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only for PostgreSQL
        DB::statement(
            "UPDATE feedback
            SET user_segment = to_jsonb(ARRAY[user_segment::jsonb->>'segment'])
            WHERE jsonb_typeof(user_segment::jsonb) = 'object'
              AND (user_segment::jsonb ?? 'segment')"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration, as we can't revert to the original object
    }
}; 