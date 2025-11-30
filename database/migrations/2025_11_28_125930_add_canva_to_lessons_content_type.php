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
        // Alter ENUM to add CANVA option
        DB::statement("ALTER TABLE lessons MODIFY COLUMN content_type ENUM('PDF', 'VIDEO', 'TEXT', 'GDRIVE', 'CANVA') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove CANVA from ENUM
        DB::statement("ALTER TABLE lessons MODIFY COLUMN content_type ENUM('PDF', 'VIDEO', 'TEXT', 'GDRIVE') NOT NULL");
    }
};
