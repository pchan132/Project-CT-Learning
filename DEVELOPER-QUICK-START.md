# 🚀 CT Learning - คู่มือนักพัฒนาฉบับเร่งด่วน

**เวอร์ชัน:** v2.0 | **วันที่:** 8 ธันวาคม 2025 | **สถานะ:** ✅ Production Ready

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

## 🏗️ สถาปัตยกรรมระบบ

### 📁 โครงสร้างไฟล์หลัก
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
│   ├── migrations/             # 🗄️ Database Schema
│   └── seeders/                # 🌱 Test Data
├── resources/views/
│   ├── admin/                  # 🔴 Admin Views
│   ├── teacher/                # 🔵 Teacher Views
│   ├── student/                # 🟢 Student Views
│   └── layouts/                # 🎨 Layout Components
├── routes/web.php               # 🛣️ Web Routes
├── storage/app/public/         # 📁 File Uploads
└── context/docs/               # 📚 Documentation
```

### 🎯 บทบาทผู้ใช้ (Roles)
| บทบาท | สี | สิทธิ์หลัก | Dashboard |
|--------|-----|-----------|-----------|
| **Admin** | 🔴 แดง | จัดการผู้ใช้, คอร์ส, สถิติ | `/admin/dashboard` |
| **Teacher** | 🔵 น้ำเงิน | สร้างคอร์ส, จัดการเนื้อหา, ตรวจแบบทดสอบ | `/teacher/dashboard` |
| **Student** | 🟢 เขียว | เรียนคอร์ส, ทำแบบทดสอบ, ดูความคืบหน้า | `/student/dashboard` |

---

## 🔧 การพัฒนา (Development)

### 📝 การสร้าง Feature ใหม่

#### 1. Controller (ตามบทบาท)
```bash
# Admin Controller
php artisan make:controller Admin/NewFeatureController

# Teacher Controller  
php artisan make:controller Teacher/NewFeatureController

# Student Controller
php artisan make:controller Student/NewFeatureController
```

#### 2. Route (เพิ่มใน `routes/web.php`)
```php
// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/new-feature', [Admin\NewFeatureController::class, 'index'])->name('admin.new-feature');
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('teacher')->group(function () {
    Route::get('/new-feature', [Teacher\NewFeatureController::class, 'index'])->name('teacher.new-feature');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('student')->group(function () {
    Route::get('/new-feature', [Student\NewFeatureController::class, 'index'])->name('student.new-feature');
});
```

#### 3. Middleware (สร้างใหม่ถ้าต้องการ)
```bash
php artisan make:middleware NewFeatureMiddleware
# เพิ่มใน `app/Http/Kernel.php`
```

#### 4. View (สร้างตามโครงสร้าง)
```
resources/views/
├── admin/
│   └── new-feature.blade.php
├── teacher/
│   └── new-feature.blade.php
└── student/
    └── new-feature.blade.php
```

### 🎨 Frontend Guidelines

#### Tailwind CSS Classes ที่ใช้บ่อย
```html
<!-- Layout -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  <main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Title</h3>
        <p class="text-gray-600 dark:text-gray-300">Content</p>
      </div>
    </div>
  </main>
</div>

<!-- Buttons -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
  Primary Button
</button>

<!-- Forms -->
<input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

<!-- Cards -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
  <!-- Content -->
</div>
```

#### Alpine.js สำหรับ Interactive
```html
<div x-data="{ isOpen: false }">
  <button @click="isOpen = !isOpen">Toggle</button>
  <div x-show="isOpen" x-transition>
    Content to show/hide
  </div>
</div>
```

### 🗄️ Database Patterns

#### Model Relationships
```php
// User Model
public function courses() {
    return $this->hasMany(Course::class, 'teacher_id');
}

public function enrollments() {
    return $this->hasMany(Enrollment::class);
}

// Course Model  
public function modules() {
    return $this->hasMany(Module::class)->orderBy('order');
}

public function teacher() {
    return $this->belongsTo(User::class, 'teacher_id');
}
```

#### Migration Pattern
```php
Schema::create('table_name', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

---

## 🔒 ความปลอดภัย

### ✅ Middleware ที่ต้องใช้
```php
// ตรวจสอบการล็อกอิน
Route::middleware(['auth'])->group(function () {
    // Routes ที่ต้องการการล็อกอิน
});

// ตรวจสอบบทบาท
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin only routes
});

Route::middleware(['auth', 'teacher'])->prefix('teacher')->group(function () {
    // Teacher only routes
});

Route::middleware(['auth', 'student'])->prefix('student')->group(function () {
    // Student only routes
});
```

### 🛡️ Validation Patterns
```php
// Request Validation
public function rules() {
    return [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
        'video' => 'nullable|file|mimes:mp4,avi,mov|max:102400', // 100MB
    ];
}

// Controller Validation
$request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,'.$user->id,
]);
```

---

## 📊 ฟีเจอร์หลัก (Key Features)

### 📚 Course Management
- **CRUD Operations**: สร้าง/แก้ไข/ลบ คอร์ส
- **Cover Image**: อัพโหลดรูปปกคอร์ส
- **Status Management**: เปิด/ปิด คอร์สเรียน
- **Student Enrollment**: ดูรายชื่อนักเรียน

### 📖 Content Management  
- **Nested Structure**: Course → Modules → Lessons
- **Multi-format**: PDF, Video, Text, Google Drive, Canva
- **Rich Text Editor**: Quill.js
- **File Upload**: จัดการไฟล์ผ่าน Laravel Storage

### 📝 Quiz System
- **Multiple Choice**: ข้อสอบปรนัย
- **Timer**: จับเวลาการทำข้อสอบ
- **Auto-grading**: ตรวจคะแนนอัตโนมัติ
- **Attempts**: บันทึกประวัติการทำข้อสอบ

### 🎓 Certificate System
- **PDF Generation**: สร้างใบประกาศนียบัตร PDF
- **Templates**: รูปแบบเอกสารที่สวยงาม
- **Auto-issuance**: สร้างอัตโนมัติเมื่อผ่านเงื่อนไข
- **Verification**: ตรวจสอบความถูกต้องของใบประกาศนียบัตร

---

## 🛠️ Commands ที่ใช้บ่อย

### Artisan Commands
```bash
# Database
php artisan migrate:fresh --seed    # รีเซ็ตฐานข้อมูลและสร้างข้อมูลทดสอบ
php artisan db:seed                 # สร้างข้อมูลทดสอบเท่านั้น

# Cache
php artisan config:cache            # Cache การตั้งค่า
php artisan route:cache             # Cache routes
php artisan view:cache              # Cache views
php artisan cache:clear             # ล้าง cache ทั้งหมด

# Storage
php artisan storage:link            # สร้าง symbolic link
php artisan queue:work              # ทำงาน queue

# Development
php artisan serve                   # เริ่ม development server
php artisan tinker                  # Laravel REPL
```

### NPM Commands
```bash
npm run dev          # Development build
npm run build        # Production build
npm run watch        # Watch for changes
npm run prod         # Optimize for production
```

---

## 🐛 Debugging & Troubleshooting

### 🔍 Common Issues

#### 1. Storage Link Issues
```bash
# ลบ link เก่าและสร้างใหม่
rm public/storage
php artisan storage:link
```

#### 2. Permission Issues
```bash
# ตั้งค่า permission สำหรับ storage
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 3. Cache Issues
```bash
# ล้าง cache ทั้งหมด
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

#### 4. Database Connection
```bash
# ตรวจสอบการเชื่อมต่อฐานข้อมูล
php artisan tinker
>>> DB::connection()->getPdo()
```

### 📝 Logging
```php
// Log ข้อมูลสำหรับ debugging
Log::info('User action', ['user_id' => auth()->id(), 'action' => 'create_course']);
Log::error('Database error', ['exception' => $e->getMessage()]);
```

---

## 📚 Resources & Documentation

### 📖 คู่มือหลัก
1. **[PROJECT-SUMMARY-2025.md](./PROJECT-SUMMARY-2025.md)** - สรุปโปรเจคล่าสุด
2. **[DEVELOPMENT-GUIDE.md](./context/docs/DEVELOPMENT-GUIDE.md)** - คู่มือพัฒนาแบบละเอียด (1,792 lines)
3. **[ARCHITECTURE.md](./context/docs/ARCHITECTURE.md)** - สถาปัตยกรรมระบบ (923 lines)

### 🔧 คู่มืออ้างอิง
4. **[ROUTES-REFERENCE.md](./context/docs/ROUTES-REFERENCE.md)** - รายการ Routes ทั้งหมด (742 lines)
5. **[QUICK-REFERENCE.md](./context/docs/QUICK-REFERENCE.md)** - คู่มือใช้งานด่วน (612 lines)
6. **[LMS-COMPLETE-GUIDE.md](./context/docs/LMS-COMPLETE-GUIDE.md)** - คู่มือระบบครบถ้วน (1,089 lines)

### 🛠️ การแก้ไขปัญหา
7. **[MODULE-LESSON-TROUBLESHOOTING.md](./context/docs/MODULE-LESSON-TROUBLESHOOTING.md)** - แก้ปัญหาระบบบทเรียน
8. **[routes-fix.md](./context/docs/routes-fix.md)** - แก้ปัญหา routes
9. **[image-upload-fix.md](./context/docs/image-upload-fix.md)** - แก้ปัญหาอัพโหลดรูป

### 📅 บันทึกการพัฒนา
10. **[DAY1-COMPLETE.md](./context/docs/DAY1-COMPLETE.md)** - Authentication & Roles
11. **[DAY2-COMPLETE.md](./context/docs/DAY2-COMPLETE.md)** - Course Management  
12. **[DAY3-COMPLETE.md](./context/docs/DAY3-COMPLETE.md)** - Module & Lesson Management
13. **[DAY4-COMPLETE.md](./context/docs/DAY4-COMPLETE.md)** - Quiz System & Certificate

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

## 🎯 Best Practices

### 📝 Code Standards
- **PSR-4 Autoloading**: ตั้งชื่อ class ตาม PSR-4
- **Laravel Conventions**: ทำตาม convention ของ Laravel
- **Tailwind CSS**: ใช้ utility classes ไม่ซ้ำซ้อน
- **Alpine.js**: เก็บ JavaScript ให้สั้นและกระชับ

### 🔒 Security
- **Validation**: ตรวจสอบข้อมูลทุกครั้งที่รับ input
- **Authorization**: ตรวจสอบสิทธิ์ก่อนดำเนินการ
- **CSRF Protection**: ใช้ CSRF token ในฟอร์มทุกฟอร์ม
- **SQL Injection**: ใช้ Eloquent ORM และ parameter binding

### 🚀 Performance
- **Eager Loading**: ใช้ `with()` เพื่อป้องกัน N+1 query
- **Caching**: Cache ข้อมูลที่ใช้บ่อย
- **Database Indexing**: สร้าง index สำหรับ foreign keys และคอลัมน์ที่ค้นหาบ่อย
- **Asset Optimization**: Minify CSS/JS และใช้ CDN

---

## 📞 Support & Contact

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
  <strong>🚀 Happy Coding! 🚀</strong><br>
  <em>CT Learning - Empowering Education Through Technology</em>
</p>