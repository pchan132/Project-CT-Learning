<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Default Template ถ้ายังไม่มี
        if (CertificateTemplate::count() === 0) {
            CertificateTemplate::create([
                'name' => 'Default Template',
                'description' => 'Template มาตรฐานสำหรับใบประกาศนียบัตร',
                'primary_color' => '#6366f1',
                'border_color' => '#d4af37',
                'text_color' => '#1f2937',
                'admin_name' => 'ผู้อำนวยการ',
                'admin_position' => 'ผู้รับรอง',
                'admin_signature_position' => 'right',
                'show_teacher_signature' => true,
                'teacher_signature_position' => 'left',
                'is_active' => true,
                'is_default' => true,
                'created_by' => 1, // Admin user
            ]);

            $this->command->info('✅ สร้าง Default Certificate Template สำเร็จ!');
        } else {
            $this->command->info('ℹ️ มี Certificate Template อยู่แล้ว');
        }
    }
}
