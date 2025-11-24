# 🚀 Quick Start Guide - CT Learning LMS

## เริ่มต้นใช้งานระบบ

### 📋 ข้อกำหนดของระบบ

- PHP 8.1 หรือสูงกว่า
- MySQL 8.0 หรือสูงกว่า
- Composer
- Node.js & NPM (สำหรับ Vite)

---

## 🛠️ ติดตั้งและเริ่มใช้งาน

### 1. Clone หรือเปิดโปรเจค
```bash
cd e:\MyWeb\Laravel\ct-learning
```

### 2. ติดตั้ง Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
# Copy .env.example to .env (if not exists)
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Setup Database
แก้ไขไฟล์ `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Start Development Server
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite (Assets)
npm run dev
```

### 7. เข้าใช้งานระบบ
เปิดเบราว์เซอร์: `http://127.0.0.1:8000`

---

## 👥 บัญชีทดสอบ

### 🔴 Admin Account
```
Email: admin@ct.ac.th
Password: password
```
**สิทธิ์:**
- จัดการผู้ใช้ทั้งหมด (CRUD)
- ดูสถิติระบบ
- เข้าถึงทุก features

### 🔵 Teacher Accounts
```
Email: teacher1@ct.ac.th
Password: password

Email: teacher2@ct.ac.th
Password: password
```
**สิทธิ์:**
- สร้าง/แก้ไข/ลบคอร์สของตัวเอง
- จัดการ Modules และ Lessons
- สร้าง Quizzes
- ดูสถิตินักเรียน

### 🟢 Student Accounts
```
Email: student1@ct.ac.th
Password: password

Email: student2@ct.ac.th
Password: password

Email: student3@ct.ac.th
Password: password

Email: student4@ct.ac.th
Password: password

Email: student5@ct.ac.th
Password: password
```
**สิทธิ์:**
- ลงทะเบียนเรียนคอร์ส
- เรียนบทเรียน
- ทำ Quizzes
- ดู Certificate

---

## 🎯 คู่มือการใช้งานแต่ละ Role

### 👨‍💼 Admin - ผู้ดูแลระบบ

**Dashboard:** `/admin/dashboard`

#### 1. จัดการผู้ใช้
- คลิก **"จัดการผู้ใช้"** ในเมนู
- ดูรายชื่อผู้ใช้ทั้งหมด
- Filter ตาม Role: All, Admin, Teacher, Student
- สร้างผู้ใช้ใหม่: คลิก **"Add New User"**
- แก้ไขผู้ใช้: คลิก **"Edit"** 
- ลบผู้ใช้: คลิก **"Delete"** (ไม่สามารถลบตัวเองได้)

#### 2. ดูสถิติระบบ
- คลิก **"สถิติระบบ"** ในเมนู
- ดูภาพรวมระบบ:
  - จำนวน Users (Admin, Teacher, Student)
  - จำนวน Courses, Modules, Lessons
  - จำนวน Enrollments
  - อัตราการเรียนจบเฉลี่ย
- Course Performance Table
- Top Teachers (ครูที่สร้างคอร์สมากที่สุด)
- Top Students (นักเรียนที่ active ที่สุด)

#### 3. สร้างผู้ใช้ใหม่
**Form Fields:**
- Full Name: ชื่อ-นามสกุล
- Email: อีเมล (ต้องไม่ซ้ำ)
- Role: เลือก Student, Teacher, หรือ Admin
- Password: รหัสผ่าน (อย่างน้อย 8 ตัวอักษร)
- Confirm Password: ยืนยันรหัสผ่าน

#### 4. แก้ไขผู้ใช้
- แก้ไขชื่อ, อีเมล, Role
- เปลี่ยนรหัสผ่าน (ถ้าต้องการ - ปล่อยว่างไว้ถ้าไม่เปลี่ยน)
- ⚠️ ไม่สามารถแก้ Role ของตัวเองได้

---

### 👨‍🏫 Teacher - อาจารย์ผู้สอน

**Dashboard:** `/teacher/dashboard`

#### 1. จัดการคอร์ส
- คลิก **"จัดการคอร์ส"** ในเมนู
- ดูคอร์สทั้งหมดของตัวเอง
- สร้างคอร์สใหม่: คลิก **"Create New Course"**
- แก้ไขคอร์ส: คลิก **"Edit"**
- ลบคอร์ส: คลิก **"Delete"**

#### 2. สร้างคอร์สใหม่
**Form Fields:**
- Course Title: ชื่อคอร์ส
- Description: รายละเอียดคอร์ส
- Image: รูปภาพหน้าปก (optional)
- Status: Published/Draft

#### 3. จัดการ Modules & Lessons
- เข้าคอร์ส → คลิก **"Modules"**
- สร้าง Module: แบ่งเนื้อหาเป็นหมวดหมู่
- สร้าง Lesson: เพิ่มบทเรียนใน Module
- จัดลำดับ Modules/Lessons (Drag & Drop)

#### 4. สร้าง Quizzes
- เข้าคอร์ส → คลิก **"Quizzes"**
- สร้าง Quiz ใหม่
- เพิ่มคำถาม:
  - Multiple Choice
  - True/False
- ตั้งค่า:
  - เวลาทำ
  - คะแนนผ่าน
  - จำนวนครั้งที่ทำได้

---

### 👨‍🎓 Student - นักเรียน

**Dashboard:** `/student/dashboard`

#### 1. ดูคอร์สที่เรียน
- Dashboard แสดงคอร์สที่ลงทะเบียนแล้ว
- ดู Progress แต่ละคอร์ส
- Continue Learning

#### 2. หาคอร์สเรียน
- คลิก **"คอร์สเรียน"** ในเมนู
- ดูคอร์สทั้งหมดที่เปิดสอน
- คลิก **"Enroll"** เพื่อลงทะเบียน

#### 3. เรียนบทเรียน
- เข้าคอร์ส → เลือก Module → เลือก Lesson
- อ่าน/ดูเนื้อหา
- คลิก **"Mark as Complete"** เมื่อเรียนจบ
- Progress จะอัพเดทอัตโนมัติ

#### 4. ทำ Quiz
- เข้าคอร์ส → เลือก Quiz
- คลิก **"Start Quiz"**
- ตอบคำถาม
- Submit Quiz
- ดูคะแนนและเฉลย

#### 5. ดู Certificate
- เรียนจบคอร์ส 100% และผ่าน Quiz
- ไปที่ **"My Certificates"**
- Download Certificate (PDF)

---

## 🎨 Features

### ✨ UI/UX
- 🌙 Dark Mode Toggle (มุมขวาบน)
- 📱 Responsive Design (Mobile, Tablet, Desktop)
- 🎨 Beautiful Stats Cards
- 🎭 Color-coded Roles:
  - Admin: 🔴 Red
  - Teacher: 🔵 Blue
  - Student: 🟢 Green

### 🔐 Security
- Password Hashing (bcrypt)
- CSRF Protection
- SQL Injection Prevention
- XSS Protection
- Role-based Access Control
- Email Verification

### 📊 Analytics
- Real-time Statistics
- Course Performance Tracking
- Student Progress Reports
- Enrollment Metrics

---

## 🛠️ ปัญหาที่พบบ่อยและวิธีแก้

### ❌ หน้าเว็บแสดงไม่ถูกต้อง (No CSS)
```bash
npm run dev
# หรือ
npm run build
```

### ❌ Database Connection Error
1. ตรวจสอบ `.env`:
   - `DB_DATABASE`: ชื่อฐานข้อมูลถูกต้องหรือไม่?
   - `DB_USERNAME` & `DB_PASSWORD`: ถูกต้องหรือไม่?
2. สร้างฐานข้อมูล:
```sql
CREATE DATABASE ct_learning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### ❌ Seeder ไม่ทำงาน
```bash
php artisan migrate:fresh --seed
```

### ❌ Login แล้วเข้า Dashboard ไม่ได้
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### ❌ 403 Forbidden Error
- ตรวจสอบว่า login ด้วย account ที่มีสิทธิ์ถูกต้อง
- Admin: เข้า `/admin/*` ได้
- Teacher: เข้า `/teacher/*` ได้
- Student: เข้า `/student/*` ได้

### ❌ Upload Image ไม่ได้
```bash
php artisan storage:link
```

---

## 📁 โครงสร้างไฟล์สำคัญ

```
ct-learning/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminController.php
│   │   │   ├── Teacher/
│   │   │   │   ├── CourseController.php
│   │   │   │   └── QuizController.php
│   │   │   └── Student/
│   │   │       └── CourseController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── TeacherMiddleware.php
│   │       └── StudentMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Course.php
│       ├── Module.php
│       ├── Lesson.php
│       ├── Quiz.php
│       └── Certificate.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── statistics.blade.php
│       │   └── users/
│       ├── teacher/
│       │   └── dashboard.blade.php
│       └── student/
│           └── dashboard.blade.php
└── routes/
    └── web.php
```

---

## 🚀 Development Workflow

### การพัฒนา Feature ใหม่

1. **สร้าง Migration:**
```bash
php artisan make:migration create_table_name
php artisan migrate
```

2. **สร้าง Model:**
```bash
php artisan make:model ModelName
```

3. **สร้าง Controller:**
```bash
php artisan make:controller ControllerName
```

4. **เพิ่ม Routes:**
```php
// routes/web.php
Route::get('/path', [Controller::class, 'method']);
```

5. **สร้าง View:**
```bash
# resources/views/folder/file.blade.php
```

### Testing Checklist
- [ ] Login ด้วยทุก role
- [ ] ทดสอบ CRUD operations
- [ ] ตรวจสอบ Authorization
- [ ] ทดสอบ Mobile Responsive
- [ ] ทดสอบ Dark Mode
- [ ] ทดสอบ Error Handling

---

## 📚 เอกสารเพิ่มเติม

- `context/docs/DAY1-COMPLETE.md` - รายละเอียด Day 1
- `context/docs/ARCHITECTURE.md` - สถาปัตยกรรมระบบ
- `context/docs/ROUTES-REFERENCE.md` - รายการ Routes ทั้งหมด
- `README.md` - ข้อมูลโปรเจค

---

## 💡 Tips & Best Practices

### ✅ Do's
- ใช้ Middleware สำหรับ Authorization
- Validate ข้อมูลทุกครั้งก่อน save
- ใช้ Eloquent Relationships
- สร้าง Seeder สำหรับ test data
- เขียน Migration ที่ชัดเจน
- ใช้ Route Names แทน URL แบบ hard-code

### ❌ Don'ts
- อย่า commit `.env` file
- อย่าเก็บ password แบบ plain text
- อย่าใช้ `DB::raw()` กับข้อมูลจาก user โดยตรง
- อย่าข้าม CSRF protection
- อย่าใช้ `*` ใน `select()` เวลาไม่จำเป็น

---

## 🎓 Learning Resources

### Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)
- [Eloquent ORM](https://laravel.com/docs/eloquent)

### Tailwind CSS
- [Tailwind Documentation](https://tailwindcss.com/docs)
- [Tailwind UI Components](https://tailwindui.com)

### Alpine.js
- [Alpine.js Documentation](https://alpinejs.dev)

---

## 📞 Support & Contact

มีปัญหาหรือข้อสงสัย?
1. ตรวจสอบ Troubleshooting Guide ด้านบน
2. อ่าน Documentation ใน `context/docs/`
3. ดู Laravel Logs: `storage/logs/laravel.log`

---

**Document Version:** 1.0  
**Last Updated:** 25 พฤศจิกายน 2025  
**System Status:** ✅ Operational
