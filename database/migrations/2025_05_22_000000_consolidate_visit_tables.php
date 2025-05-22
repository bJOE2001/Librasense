<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, create the new consolidated table
        Schema::create('library_visits_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('visitor_name');
            $table->string('visitor_type'); // student, non_student, guest
            $table->timestamp('entry_time');
            $table->timestamp('exit_time')->nullable();
            $table->integer('duration_minutes')->nullable()->comment('Time spent in library in minutes');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });

        // Migrate data from both tables to the new table (omit location and purpose)
        DB::statement('
            INSERT INTO library_visits_new (
                user_id, visitor_name, visitor_type, entry_time, exit_time, 
                duration_minutes, qr_code, created_at, updated_at
            )
            SELECT 
                va.user_id,
                COALESCE(u.name, \'Guest\') as visitor_name,
                va.visitor_type,
                va.entry_time::timestamp,
                va.exit_time::timestamp,
                EXTRACT(EPOCH FROM (va.exit_time::timestamp - va.entry_time::timestamp))/60 as duration_minutes,
                va.qr_code,
                va.created_at,
                va.updated_at
            FROM visitor_analytics va
            LEFT JOIN users u ON va.user_id = u.id
        ');

        // Add any data from library_visits that isn't in visitor_analytics
        DB::statement('
            INSERT INTO library_visits_new (
                user_id, visitor_name, visitor_type, entry_time, exit_time, 
                duration_minutes, created_at, updated_at
            )
            SELECT 
                lv.user_id,
                lv.name,
                lv.type,
                lv.entry_time,
                lv.exit_time,
                lv.duration_minutes,
                lv.created_at,
                lv.updated_at
            FROM library_visits lv
            WHERE NOT EXISTS (
                SELECT 1 FROM library_visits_new lvn 
                WHERE lvn.user_id = lv.user_id 
                AND lvn.entry_time = lv.entry_time
            )
        ');

        // Drop the old tables
        Schema::dropIfExists('visitor_analytics');
        Schema::dropIfExists('library_visits');

        // Rename the new table to library_visits
        Schema::rename('library_visits_new', 'library_visits');
    }

    public function down()
    {
        // Recreate the original tables
        Schema::create('visitor_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('visitor_type');
            $table->string('entry_time');
            $table->string('exit_time')->nullable();
            $table->string('location');
            $table->string('purpose');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });

        Schema::create('library_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->timestamp('entry_time');
            $table->timestamp('exit_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
        });

        // Migrate data back to original tables
        DB::statement('
            INSERT INTO visitor_analytics (
                user_id, visitor_type, entry_time, exit_time, 
                location, purpose, qr_code, created_at, updated_at
            )
            SELECT 
                user_id,
                visitor_type,
                entry_time::text,
                exit_time::text,
                NULL as location,
                NULL as purpose,
                qr_code,
                created_at,
                updated_at
            FROM library_visits
        ');

        DB::statement('
            INSERT INTO library_visits (
                user_id, name, type, entry_time, exit_time, 
                duration_minutes, created_at, updated_at
            )
            SELECT 
                user_id,
                visitor_name,
                visitor_type,
                entry_time,
                exit_time,
                duration_minutes,
                created_at,
                updated_at
            FROM library_visits
            WHERE user_id IS NOT NULL
        ');
    }
}; 