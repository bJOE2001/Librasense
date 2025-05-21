<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->text('description');
            $table->string('category');
            $table->integer('quantity');
            $table->string('location');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
        // After table creation, update all existing books
        DB::table('books')->update(['is_available' => DB::raw('quantity > 0')]);
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
}; 