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
        DB::table('users')->truncate();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed as truncate is irreversible
    }
};
