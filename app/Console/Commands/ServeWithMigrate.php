<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Illuminate\Support\Facades\Schema;

class ServeWithMigrate extends ServeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application (with auto-migration for new installations)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // ตรวจสอบว่าเป็นการติดตั้งใหม่หรือไม่ (ไม่มี table users)
        if ($this->isNewInstallation()) {
            $this->info('');
            $this->info('🔍 ตรวจพบว่าเป็นการติดตั้งใหม่...');
            $this->info('');

            // Run migrations
            $this->info('📦 กำลัง migrate ฐานข้อมูล...');
            $this->call('migrate', ['--force' => true]);
            $this->info('✅ Migrate สำเร็จ!');
            $this->info('');

            // ถามว่าต้องการ seed ข้อมูลตัวอย่างหรือไม่
            if ($this->confirm('🌱 ต้องการเพิ่มข้อมูลตัวอย่าง (seed) หรือไม่?', true)) {
                $this->info('📦 กำลัง seed ข้อมูล...');
                $this->call('db:seed');
                $this->info('✅ Seed สำเร็จ!');
            }

            // สร้าง storage link ถ้ายังไม่มี
            if (!file_exists(public_path('storage'))) {
                $this->info('🔗 กำลังสร้าง storage link...');
                $this->call('storage:link');
                $this->info('✅ สร้าง storage link สำเร็จ!');
            }

            $this->info('');
            $this->info('🎉 ติดตั้งระบบเสร็จสมบูรณ์!');
            $this->info('');
        }

        // รัน serve ตามปกติ
        return parent::handle();
    }

    /**
     * ตรวจสอบว่าเป็นการติดตั้งใหม่หรือไม่
     */
    protected function isNewInstallation(): bool
    {
        try {
            // ถ้าไม่มี table 'users' แสดงว่าเป็นการติดตั้งใหม่
            return !Schema::hasTable('users');
        } catch (\Exception $e) {
            // ถ้าเชื่อมต่อ database ไม่ได้ ให้แจ้งเตือน
            $this->error('❌ ไม่สามารถเชื่อมต่อฐานข้อมูลได้!');
            $this->error('กรุณาตรวจสอบการตั้งค่าใน .env file');
            $this->info('');
            $this->info('ตัวอย่างการตั้งค่า:');
            $this->info('DB_CONNECTION=mysql');
            $this->info('DB_HOST=127.0.0.1');
            $this->info('DB_PORT=3306');
            $this->info('DB_DATABASE=ct_learning');
            $this->info('DB_USERNAME=root');
            $this->info('DB_PASSWORD=');
            $this->info('');

            return false;
        }
    }
}
