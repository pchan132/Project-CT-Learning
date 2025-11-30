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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('role');
            $table->string('position')->nullable()->after('profile_image'); // ตำแหน่ง เช่น หัวหน้าแผนกวิชา, ครูประจำ
            $table->text('bio')->nullable()->after('position'); // ประวัติย่อ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_image', 'position', 'bio']);
        });
    }
};
