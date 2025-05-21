<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns as nullable
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('user_id');
            $table->text('message')->nullable()->after('subject');
        });

        // Update existing records
        DB::table('feedback')->update([
            'subject' => 'General Feedback',
            'message' => DB::raw("CASE 
                WHEN comment IS NOT NULL THEN comment 
                ELSE 'No message provided'
                END"),
        ]);

        // Make columns required
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('subject')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
        });

        // Drop old columns
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['rating', 'feedback_type', 'comment', 'is_anonymous']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->integer('rating');
            $table->string('feedback_type');
            $table->text('comment');
            $table->boolean('is_anonymous')->default(false);
            $table->dropColumn(['subject', 'message']);
        });
    }
};
