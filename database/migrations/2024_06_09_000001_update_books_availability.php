<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update all books so is_available matches quantity > 0
        DB::table('books')->update(['is_available' => DB::raw('quantity > 0')]);
    }

    public function down()
    {
        // Optionally, you could set all to true, but it's not strictly necessary
        DB::table('books')->update(['is_available' => true]);
    }
}; 