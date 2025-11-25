<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Enrollment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // สร้าง Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ct.ac.th',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // สร้าง Teachers
        $teacher1 = User::create([
            'name' => 'อาจารย์สมชาย ใจดี',
            'email' => 'teacher1@ct.ac.th',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        $teacher2 = User::create([
            'name' => 'อาจารย์สมหญิง รักเรียน',
            'email' => 'teacher2@ct.ac.th',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // สร้าง Students
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $students[] = User::create([
                'name' => "นักเรียน คนที่ $i",
                'email' => "student$i@ct.ac.th",
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        // สร้าง Courses
        $course1 = Course::create([
            'teacher_id' => $teacher1->id,
            'title' => 'พื้นฐานการเขียนโปรแกรม PHP',
            'description' => 'เรียนรู้การเขียนโปรแกรม PHP ตั้งแต่พื้นฐานจนถึงขั้นสูง เหมาะสำหรับผู้เริ่มต้น',
            'cover_image_url' => null,
        ]);

        $course2 = Course::create([
            'teacher_id' => $teacher1->id,
            'title' => 'การพัฒนาเว็บด้วย Laravel Framework',
            'description' => 'เรียนรู้การสร้างเว็บแอพพลิเคชันด้วย Laravel Framework อย่างมืออาชีพ',
            'cover_image_url' => null,
        ]);

        $course3 = Course::create([
            'teacher_id' => $teacher2->id,
            'title' => 'ฐานข้อมูล MySQL',
            'description' => 'เรียนรู้การออกแบบและจัดการฐานข้อมูล MySQL อย่างมีประสิทธิภาพ',
            'cover_image_url' => null,
        ]);

        // สร้าง Modules สำหรับ Course 1
        $module1 = Module::create([
            'course_id' => $course1->id,
            'title' => 'บทที่ 1: แนะนำ PHP',
            'description' => 'ความรู้พื้นฐานเกี่ยวกับภาษา PHP และการติดตั้ง',
            'order' => 1,
        ]);

        $module2 = Module::create([
            'course_id' => $course1->id,
            'title' => 'บทที่ 2: ตัวแปรและชนิดข้อมูล',
            'description' => 'เรียนรู้เกี่ยวกับตัวแปร ชนิดข้อมูล และการใช้งาน',
            'order' => 2,
        ]);

        // สร้าง Lessons สำหรับ Module 1
        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'บทเรียนที่ 1.1: PHP คืออะไร',
            'content_type' => 'TEXT',
            'content_text' => '<h2>PHP คืออะไร?</h2><p>PHP (Hypertext Preprocessor) เป็นภาษาสคริปต์ฝั่งเซิร์ฟเวอร์ ที่ใช้ในการพัฒนาเว็บแอปพลิเคชัน มีความยืดหยุ่นสูง และใช้งานง่าย</p>',
            'content_url' => null,
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'บทเรียนที่ 1.2: การติดตั้ง PHP',
            'content_type' => 'VIDEO',
            'content_text' => null,
            'content_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'order' => 2,
        ]);

        // สร้าง Lessons สำหรับ Module 2
        Lesson::create([
            'module_id' => $module2->id,
            'title' => 'บทเรียนที่ 2.1: ตัวแปรใน PHP',
            'content_type' => 'TEXT',
            'content_text' => '<h2>ตัวแปรใน PHP</h2><p>ตัวแปรใน PHP เริ่มต้นด้วยเครื่องหมาย $ ตามด้วยชื่อตัวแปร เช่น $name, $age</p>',
            'content_url' => null,
            'order' => 1,
        ]);

        // สร้าง Modules สำหรับ Course 2
        $module3 = Module::create([
            'course_id' => $course2->id,
            'title' => 'บทที่ 1: แนะนำ Laravel',
            'description' => 'ทำความรู้จักกับ Laravel Framework',
            'order' => 1,
        ]);

        Lesson::create([
            'module_id' => $module3->id,
            'title' => 'บทเรียนที่ 1.1: Laravel คืออะไร',
            'content_type' => 'TEXT',
            'content_text' => '<h2>Laravel Framework</h2><p>Laravel เป็น PHP Framework ที่ได้รับความนิยมสูงสุดในปัจจุบัน</p>',
            'content_url' => null,
            'order' => 1,
        ]);

        // ลงทะเบียนนักเรียนในคอร์ส
        Enrollment::create([
            'course_id' => $course1->id,
            'student_id' => $students[0]->id,
        ]);

        Enrollment::create([
            'course_id' => $course1->id,
            'student_id' => $students[1]->id,
        ]);

        Enrollment::create([
            'course_id' => $course2->id,
            'student_id' => $students[0]->id,
        ]);

        $this->command->info('✅ Seeded successfully!');
        $this->command->info('📧 Admin: admin@ct.ac.th / password');
        $this->command->info('📧 Teacher 1: teacher1@ct.ac.th / password');
        $this->command->info('📧 Teacher 2: teacher2@ct.ac.th / password');
        $this->command->info('📧 Student: student1@ct.ac.th - student5@ct.ac.th / password');
    }
}
