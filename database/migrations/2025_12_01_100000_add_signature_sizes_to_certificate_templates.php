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
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->integer('admin_signature_width')->default(120)->after('admin_signature');
            $table->integer('admin_signature_height')->default(50)->after('admin_signature_width');
            $table->integer('teacher_signature_width')->default(120)->after('teacher_signature_position');
            $table->integer('teacher_signature_height')->default(50)->after('teacher_signature_width');
            $table->integer('logo_width')->default(80)->after('logo_image');
            $table->integer('logo_height')->default(80)->after('logo_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn([
                'admin_signature_width',
                'admin_signature_height',
                'teacher_signature_width',
                'teacher_signature_height',
                'logo_width',
                'logo_height',
            ]);
        });
    }
};
