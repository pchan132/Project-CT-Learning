# 🎓 CT Learning - Complete Learning Management System

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![Status](https://img.shields.io/badge/status-Production%20Ready-brightgreen.svg)
![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)

**ระบบจัดการการเรียนการสอนออนไลน์ (LMS) ที่สมบูรณ์แบบ** พัฒนาด้วย Laravel 10.x พร้อมฟีเจอร์ครบถ้วนสำหรับการสอนแบบ Multi-media ระบบแบบทดสอบ และการออกใบประกาศนียบัตรอัตโนมัติ ✅ **Production Ready**

## 📋 สารบัญ

- [🎯 ภาพรวมระบบ](#-ภาพรวมระบบ)
- [✅ ฟีเจอร์ที่พร้อมใช้งาน](#-ฟีเจอร์ที่พร้อมใช้งาน)
- [🏗️ สถาปัตยกรรมระบบ](#️-สถาปัตยกรรมระบบ)
- [⚡ Quick Start](#-quick-start)
- [🛠️ การติดตั้ง](#️-การติดตั้ง)
- [👥 บทบาทผู้ใช้](#-บทบาทผู้ใช้)
- [📚 เอกสารประกอบ](#-เอกสารประกอบ)
- [🚀 Deployment](#-deployment)
- [📞 Contact & Support](#-contact--support)

---

## 🎯 ภาพรวมระบบ

CT Learning เป็นระบบ Learning Management System (LMS) ที่พัฒนาด้วย Laravel 10.x สำหรับแผนกเทคโนโลยีคอมพิวเตอร์ มีฟีเจอร์ครบถ้วนสำหรับการสอนแบบ Multi-media พร้อมระบบ Authentication แยกตามบทบาท และระบบจัดการเนื้อหาที่สมบูรณ์

### 🎯 วัตถุประสงค์หลัก
- **สำหรับครูผู้สอน**: สร้างและจัดการคอร์สเรียนออนไลน์ได้อย่างสมบูรณ์
- **สำหรับนักเรียน**: เข้าเรียนและติดตามความคืบหน้าการเรียนได้ง่าย
- **สำหรับผู้ดูแลระบบ**: จัดการผู้ใช้และคอร์สเรียนได้อย่างมีประสิทธิภาพ

---

## ✅ ฟีเจอร์ที่พร้อมใช้งาน (100% Complete)

### 🔐 ระบบ Authentication & Authorization
- ✅ **Multi-role System**: Student, Teacher, Admin
- ✅ **Separated Registration**: ลงทะเบียนแยกระหว่างนักเรียนและครูผู้สอน
- ✅ **Role-based Middleware**: ควบคุมการเข้าถึงตามสิทธิ์
- ✅ **Auto Dashboard Redirect**: นำทางไปยังหน้าหลักตามบทบาท
- ✅ **Email Verification**: ยืนยันอีเมลผู้ใช้
- ✅ **Password Reset**: รีเซ็ตรหัสผ่าน

### 👥 ระบบจัดการผู้ใช้ (Admin)
- ✅ **User Management**: สร้าง แก้ไข ลบ ผู้ใช้ทั้งหมด
- ✅ **Role Assignment**: กำหนดและเปลี่ยนบทบาทผู้ใช้
- ✅ **User Statistics**: สถิติการใช้งานของผู้ใช้
- ✅ **Filter by Role**: กรองผู้ใช้ตามบทบาท

### 📚 ระบบการจัดการคอร์สเรียน (Teacher)
- ✅ **Course CRUD**: สร้าง แก้ไข ลบ คอร์สเรียน
- ✅ **Cover Image Upload**: อัพโหลดรูปปกคอร์สเรียน
- ✅ **Course Categories**: จัดหมวดหมู่คอร์สเรียน
- ✅ **Course Status**: เปิด/ปิด คอร์สเรียน
- ✅ **Student Enrollment**: ดูรายชื่อนักเรียนที่ลงทะเบียน

### 📖 ระบบจัดการเนื้อหาการสอน
- ✅ **Nested Structure**: Course → Modules → Lessons
- ✅ **Multi-format Content**: 
  - 📄 **PDF/Documents**: รองรับ PDF, DOC, DOCX, PPT, PPTX (10MB)
  - 🎥 **Video**: YouTube, Vimeo, Direct MP4, Video Upload (100MB)
  - 📝 **Text Articles**: Rich Text Editor (Quill.js)
  - 🌐 **Google Drive**: ฝังเนื้อหาจาก Google Drive
  - 🎨 **Canva**: ฝังผลงานจาก Canva
- ✅ **File Management**: จัดการไฟล์ผ่าน Laravel Storage
- ✅ **Content Ordering**: จัดลำดับเนื้อหาได้ (Drag & Drop)

### 📝 ระบบแบบทดสอบ (Quiz System)
- ✅ **Quiz Creation**: สร้างแบบทดสอบในแต่ละ Module
- ✅ **Question Types**: Multiple Choice (พร้อมแผนขยาย)
- ✅ **Timer Support**: กำหนดเวลาในการทำแบบทดสอบ
- ✅ **Auto-grading**: ตรวจและคำนวณคะแนนอัตโนมัติ
- ✅ **Passing Score**: กำหนดคะแนนผ่านต่อแบบทดสอบ
- ✅ **Attempt Tracking**: บันทึกประวัติการทำแบบทดสอบ
- ✅ **Results Analysis**: แสดงผลลัพธ์พร้อมสถิติ
- ✅ **Real-time Timer**: นับถอยหลังพร้อม auto-submit

### 🎓 ระบบใบประกาศนียบัตร (Certificate System)
- ✅ **Automatic Generation**: สร้าง PDF อัตโนมัติเมื่อผ่านเงื่อนไข
- ✅ **Certificate Templates**: รูปแบบเอกสารที่สวยงาม
- ✅ **Unique Certificate Numbers**: เลขที่อ้างอิงไม่ซ้ำกัน
- ✅ **Download & Share**: ดาวน์โหลดและแชร์ได้
- ✅ **Verification System**: ตรวจสอบความถูกต้องของใบประกาศนียบัตร
- ✅ **Eligibility Validation**: ตรวจสอบความสมบูรณ์ของการเรียน

### 📊 ระบบติดตามความคืบหน้า (Progress Tracking)
- ✅ **Real-time Progress**: อัพเดทความคืบหน้าแบบ real-time
- ✅ **Progress Visualization**: Progress bars และ completion badges
- ✅ **Lesson Completion Tracking**: บันทึกการเรียนเสร็จแต่ละบทเรียน
- ✅ **Course Completion**: ติดตามการเรียนจบคอร์ส
- ✅ **Statistics Dashboard**: สถิติการเรียนสำหรับครูและผู้ดูแล
- ✅ **AJAX Completion**: บันทึกความคืบหน้าโดยไม่ต้อง reload

### 🎨 ส่วนติดต่อผู้ใช้ (UI/UX)
- ✅ **Responsive Design**: รองรับทุกขนาดหน้าจอ (Mobile, Tablet, Desktop)
- ✅ **Dark Mode Support**: สลับโหมดมืด/สว่างได้
- ✅ **Modern UI**: ใช้ Tailwind CSS พร้อม Glass Morphism design
- ✅ **Interactive Elements**: Hover effects, transitions, micro-interactions
- ✅ **Accessibility**: รองรับการเข้าถึงสำหรับผู้พิการ
- ✅ **Color-coded Roles**: Admin (🔴 Red), Teacher (🔵 Blue), Student (🟢 Green)

---

## 🏗️ สถาปัตยกรรมระบบ

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage System
- **PDF Generation**: DomPDF
- **Build Tools**: Vite + NPM

### Database Schema
```
users (Student, Teacher, Admin)
├── courses (teacher_id)
│   ├── modules (course_id, order)
│   │   └── lessons (module_id, content_type, order)
│   └── enrollments (user_id, course_id)
├── quizzes (module_id)
│   ├── questions (quiz_id, order)
│   │   └── answers (question_id, is_correct)
│   └── quiz_attempts (user_id, score, passed)
├── lesson_completions (user_id, lesson_id)
└── certificates (user_id, course_id, certificate_number)
```

### 🏛️ โครงสร้างโปรเจค
```
ct-learning/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # 🔴 Admin Controllers
│   │   ├── Teacher/            # 🔵 Teacher Controllers  
│   │   ├── Student/            # 🟢 Student Controllers
│   │   └── Auth/               # Authentication
│   ├── Models/                 # 📊 Eloquent Models
│   └── Middleware/             # 🛡️ Role-based Middleware
├── database/
│   ├── migrations/             # 🗄️ Database Migrations
│   └── seeders/                # 🌱 Test Data
├── resources/views/
│   ├── admin/                  # 🔴 Admin Views
│   ├── teacher/                # 🔵 Teacher Views
│   ├── student/                # 🟢 Student Views
│   └── layouts/                # 🎨 Layout Components
├── routes/web.php               # 🛣️ Web Routes
├── storage/app/public/         # 📁 File Uploads
└── context/docs/               # 📚 Complete Documentation
```

---

## ⚡ Quick Start (5 นาที)

### 📋 ความต้องการระบบ
- **PHP 8.1+** และ **Composer**
- **MySQL 8.0+** หรือ **MariaDB 10.3+**
- **Node.js 16+** และ **NPM**
- **Git**

### 🛠️ การติดตั้ง

```bash
# 1. Clone Repository
git clone https://github.com/pchan132/Project-CT-Learning.git
cd Project-CT-Learning

# 2. Install Dependencies
composer install
npm install

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Database Configuration
# แก้ไข .env ตั้งค่าฐานข้อมูล
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=root
DB_PASSWORD=

# 5. Database Setup
php artisan migrate:fresh --seed
php artisan storage:link

# 6. Start Development Server
php artisan serve
npm run dev
```

### 🌐 เข้าใช้งานระบบ
- **Application**: http://127.0.0.1:8000
- **Admin**: admin@ct.ac.th / password
- **Teacher**: teacher1@ct.ac.th / password  
- **Student**: student1@ct.ac.th / password

---

## 👥 บทบาทผู้ใช้

| บทบาท | สี | สิทธิ์หลัก | Dashboard |
|--------|-----|-----------|-----------|
| **Admin** | 🔴 แดง | จัดการผู้ใช้, คอร์ส, สถิติ | `/admin/dashboard` |
| **Teacher** | 🔵 น้ำเงิน | สร้างคอร์ส, จัดการเนื้อหา, ตรวจแบบทดสอบ | `/teacher/dashboard` |
| **Student** | 🟢 เขียว | เรียนคอร์ส, ทำแบบทดสอบ, ดูความคืบหน้า | `/student/dashboard` |

### บัญชีทดสอบ
| Role | Email | Password | คำอธิบาย |
|------|-------|----------|-----------|
| **Admin** | admin@ct.ac.th | password | ผู้ดูแลระบบ |
| **Teacher** | teacher1@ct.ac.th | password | ครูผู้สอนคนที่ 1 |
| **Teacher** | teacher2@ct.ac.th | password | ครูผู้สอนคนที่ 2 |
| **Student** | student1@ct.ac.th | password | นักเรียนคนที่ 1 |
| **Student** | student2@ct.ac.th | password | นักเรียนคนที่ 2 |
| **Student** | student3@ct.ac.th | password | นักเรียนคนที่ 3 |

---

## 📚 เอกสารประกอบ (Complete Documentation)

### 📖 คู่มือหลัก
1. **[PROJECT-SUMMARY-2025.md](./PROJECT-SUMMARY-2025.md)** - สรุปโปรเจคล่าสุด
2. **[DEVELOPER-QUICK-START.md](./DEVELOPER-QUICK-START.md)** - คู่มือนักพัฒนาฉบับเร่งด่วน
3. **[DEVELOPMENT-GUIDE.md](./context/docs/DEVELOPMENT-GUIDE.md)** - คู่มือพัฒนาแบบละเอียด (1,792 lines)
4. **[ARCHITECTURE.md](./context/docs/ARCHITECTURE.md)** - สถาปัตยกรรมระบบ (923 lines)

### 🔧 คู่มืออ้างอิง
5. **[ROUTES-REFERENCE.md](./context/docs/ROUTES-REFERENCE.md)** - รายการ Routes ทั้งหมด (742 lines)
6. **[QUICK-REFERENCE.md](./context/docs/QUICK-REFERENCE.md)** - คู่มือใช้งานด่วน (612 lines)
7. **[LMS-COMPLETE-GUIDE.md](./context/docs/LMS-COMPLETE-GUIDE.md)** - คู่มือระบบครบถ้วน (1,089 lines)

### 🛠️ การแก้ไขปัญหา
8. **[MODULE-LESSON-TROUBLESHOOTING.md](./context/docs/MODULE-LESSON-TROUBLESHOOTING.md)** - แก้ปัญหาระบบบทเรียน
9. **[routes-fix.md](./context/docs/routes-fix.md)** - แก้ปัญหา routes
10. **[image-upload-fix.md](./context/docs/image-upload-fix.md)** - แก้ปัญหาอัพโหลดรูป

### 📅 บันทึกการพัฒนา
11. **[DAY1-COMPLETE.md](./context/docs/DAY1-COMPLETE.md)** - Authentication & Roles
12. **[DAY2-COMPLETE.md](./context/docs/DAY2-COMPLETE.md)** - Course Management  
13. **[DAY3-COMPLETE.md](./context/docs/DAY3-COMPLETE.md)** - Module & Lesson Management
14. **[DAY4-COMPLETE.md](./context/docs/DAY4-COMPLETE.md)** - Quiz System & Certificate

---

## 🚀 Deployment

### 🔧 Production Checklist
```bash
# 1. Environment
APP_ENV=production
APP_DEBUG=false

# 2. Database
php artisan migrate --force

# 3. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Assets
npm run build

# 5. Permissions
chmod -R 775 storage bootstrap/cache

# 6. Optimize
composer install --optimize-autoloader --no-dev
```

### 🌐 Server Requirements
- **PHP**: 8.1+ พร้อม extensions: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`
- **Database**: MySQL 8.0+ หรือ MariaDB 10.3+
- **Web Server**: Nginx หรือ Apache พร้อม SSL
- **Node.js**: 16+ (สำหรับ build assets)

---

## 📞 Contact & Support

### 🏫 Team
- **Project Lead**: [Pchan132](https://github.com/pchan132)
- **GitHub**: https://github.com/pchan132/Project-CT-Learning
- **Issues**: https://github.com/pchan132/Project-CT-Learning/issues

### 💬 การขอความช่วยเหลือ
1. **ตรวจสอบ logs**: `storage/logs/laravel.log`
2. **ค้นหาใน documentation**: `context/docs/`
3. **สร้าง GitHub Issue**: พร้อมรายละเอียดปัญหา
4. **ติดต่อทีม**: dev@ct.ac.th

---

## 🎉 Summary

**CT Learning LMS v2.0** เป็นระบบที่พร้อมใช้งานจริง พัฒนาด้วยเทคโนโลยีล่าสุด มีเอกสารครบถ้วน และเป็นไปตามมาตรฐานการพัฒนาที่ดีที่สุด

### ✅ พร้อมใช้งาน:
- **Complete LMS System**: ครบถ้วนทุกฟีเจอร์
- **Multi-role Architecture**: Admin/Teacher/Student  
- **Modern UI/UX**: Responsive + Dark Mode
- **Rich Content**: PDF/Video/Text/Google Drive/Canva
- **Assessment System**: Quiz + Auto-grading
- **Certificate System**: PDF Generation
- **Progress Tracking**: Real-time Analytics
- **Complete Documentation**: 20+ เอกสาร

### 🚀 เริ่มต้นได้ทันที:
```bash
git clone https://github.com/pchan132/Project-CT-Learning.git
cd Project-CT-Learning
composer install && npm install
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve & npm run dev
```

---

**Created:** 8 ธันวาคม 2025  
**Version:** 2.0.0 (Production Ready)  
**Documentation:** 100% Complete  
**Status:** ✅ Ready for Production

---

<p align="center">
  <strong>🚀 CT Learning - Complete LMS System 🚀</strong><br>
  <em>Empowering Education Through Technology</em><br>
  Made with ❤️ using Laravel, Tailwind CSS & Modern Web Technologies
</p>