<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('sentiment')->nullable()->after('message');
            $table->json('topics')->nullable()->after('sentiment');
            $table->json('frequent_patterns')->nullable()->after('topics');
            $table->boolean('is_anomaly')->default(false)->after('frequent_patterns');
            $table->json('user_segment')->nullable()->after('is_anomaly');
            $table->json('trend_data')->nullable()->after('user_segment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn([
                'sentiment',
                'topics',
                'frequent_patterns',
                'is_anomaly',
                'user_segment',
                'trend_data'
            ]);
        });
    }
}; 