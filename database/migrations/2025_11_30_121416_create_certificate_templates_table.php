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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อ template
            $table->text('description')->nullable(); // คำอธิบาย
            $table->string('background_image')->nullable(); // รูปพื้นหลัง
            $table->string('border_color')->default('#d4af37'); // สีกรอบ
            $table->string('primary_color')->default('#d4af37'); // สีหลัก
            $table->string('text_color')->default('#333333'); // สีตัวอักษร
            $table->string('logo_image')->nullable(); // โลโก้สถาบัน
            $table->string('admin_signature')->nullable(); // ลายเซ็น Admin/ผู้อำนวยการ
            $table->string('admin_name')->nullable(); // ชื่อ Admin ที่แสดง
            $table->string('admin_position')->nullable(); // ตำแหน่ง Admin
            $table->boolean('show_teacher_signature')->default(true); // แสดงลายเซ็นครูหรือไม่
            $table->string('teacher_signature_position')->default('right'); // ตำแหน่งลายเซ็นครู (left/right)
            $table->string('admin_signature_position')->default('left'); // ตำแหน่งลายเซ็น admin (left/right)
            $table->boolean('is_active')->default(false); // template ที่ใช้งานอยู่
            $table->boolean('is_default')->default(false); // template เริ่มต้น
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // เพิ่ม column ลายเซ็นใน users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('signature_image')->nullable()->after('bio');
        });

        // เพิ่ม column template_id ใน certificates table
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('course_id')->constrained('certificate_templates')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('signature_image');
        });

        Schema::dropIfExists('certificate_templates');
    }
};
