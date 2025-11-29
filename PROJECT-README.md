# 🎓 CT Learning - Learning Management System

ระบบจัดการการเรียนการสอนออนไลน์ (LMS) สำหรับแผนกเทคโนโลยีคอมพิวเตอร์ พัฒนาด้วย Laravel 10 พร้อมฟีเจอร์ครบครันสำหรับการสอนแบบ Multi-media

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC)
![License](https://img.shields.io/badge/license-MIT-green)

---

## 📋 ภาพรวมระบบ

CT Learning เป็นระบบ Learning Management System (LMS) ที่พัฒนาขึ้นโดยเฉพาะสำหรับแผนกเทคโนโลยีคอมพิวเตอร์ มีวัตถุประสงค์เพื่อให้บริการการเรียนการสอนออนไลน์ที่ครบวงจร รองรับการจัดการคอร์สเรียน บทเรียน การประเมิน และการออกเอกสารรับรอง

### 🎯 วัตถุประสงค์หลัก
- **สำหรับครูผู้สอน**: สร้างและจัดการคอร์สเรียนออนไลน์ได้อย่างสมบูรณ์
- **สำหรับนักเรียน**: เข้าเรียนและติดตามความคืบหน้าการเรียนได้ง่าย
- **สำหรับผู้ดูแลระบบ**: จัดการผู้ใช้และคอร์สเรียนได้อย่างมีประสิทธิภาพ

### ✨ ฟีเจอร์หลักที่พร้อมใช้งาน

#### 🔐 ระบบ Authentication & Authorization
- **Multi-role System**: Student, Teacher, Admin
- **Separated Registration**: ลงทะเบียนแยกระหว่างนักเรียนและครูผู้สอน
- **Role-based Middleware**: ควบคุมการเข้าถึงตามสิทธิ์
- **Auto Dashboard Redirect**: นำทางไปยังหน้าหลักตามบทบาท

#### 📚 ระบบการจัดการคอร์สเรียน
- **Course CRUD**: สร้าง แก้ไข ลบ คอร์สเรียน
- **Cover Image Upload**: อัพโหลดรูปปกคอร์สเรียน
- **Course Categories**: จัดหมวดหมู่คอร์สเรียน (ถ้ามี)
- **Course Status**: เปิด/ปิด คอร์สเรียน

#### 📖 ระบบจัดการเนื้อหาการสอน
- **Nested Structure**: Course → Modules → Lessons
- **Multi-format Content**: 
  - 📄 **PDF/Documents**: รองรับ PDF, DOC, DOCX, PPT, PPTX
  - 🎥 **Video**: YouTube video embedding
  - 📝 **Text Articles**: เขียนบทความพร้อม formatting
  - 🌐 **Google Drive**: ฝังเนื้อหาจาก Google Drive
  - 🎨 **Canva**: ฝังผลงานจาก Canva
- **File Management**: จัดการไฟล์ผ่าน Laravel Storage
- **Content Ordering**: จัดลำดับเนื้อหาได้

#### 📝 ระบบการประเมินผล
- **Quiz System**: สร้างแบบทดสอบในแต่ละ Module
- **Question Types**: Multiple Choice (พร้อมแผนขยายรองรับประเภทอื่น)
- **Timer Support**: กำหนดเวลาในการทำแบบทดสอบ
- **Auto-grading**: ตรวจและคำนวณคะแนนอัตโนมัติ
- **Passing Score**: กำหนดคะแนนผ่านต่อแบบทดสอบ
- **Attempt Tracking**: บันทึกประวัติการทำแบบทดสอบ
- **Results Analysis**: แสดงผลลัพธ์พร้อมสถิติ

#### 🎓 ระบบใบประกาศนียบัตร
- **Automatic Generation**: สร้าง PDF อัตโนมัติเมื่อผ่านเงื่อนไข
- **Certificate Templates**: รูปแบบเอกสารที่สวยงาม
- **Unique Certificate Numbers**: เลขที่อ้างอิงไม่ซ้ำกัน
- **Download & Share**: ดาวน์โหลดและแชร์ได้
- **Verification System**: ตรวจสอบความถูกต้องของใบประกาศนียบัตร

#### 📊 ระบบติดตามความคืบหน้า
- **Real-time Progress**: อัพเดทความคืบหน้าแบบ real-time
- **Progress Visualization**: Progress bars และ completion badges
- **Lesson Completion Tracking**: บันทึกการเรียนเสร็จแต่ละบทเรียน
- **Course Completion**: ติดตามการเรียนจบคอร์ส
- **Statistics Dashboard**: สถิติการเรียนสำหรับครูและผู้ดูแล

#### 👥 ระบบจัดการผู้ใช้ (Admin)
- **User Management**: สร้าง แก้ไข ลบ ผู้ใช้
- **Role Assignment**: กำหนดและเปลี่ยนบทบาทผู้ใช้
- **Bulk Operations**: จัดการผู้ใช้แบบกลุ่ม
- **User Statistics**: สถิติการใช้งานของผู้ใช้

#### 🎨 ส่วนติดต่อผู้ใช้ (UI/UX)
- **Responsive Design**: รองรับทุกขนาดหน้าจอ (Mobile, Tablet, Desktop)
- **Dark Mode Support**: สลับโหมดมืด/สว่างได้
- **Modern UI**: ออกแบบด้วย Tailwind CSS และ Glass Morphism
- **Interactive Elements**: Hover effects, transitions, micro-interactions
- **Accessibility**: รองรับการเข้าถึงสำหรับผู้พิการ
- **Multi-language Support**: โครงสร้างพร้อมรองรับหลายภาษา (แผน)

---

## 🚀 ฟีเจอร์สำหรับผู้ใช้แต่ละประเภท

### 👨‍🏫 สำหรับครูผู้สอน (Teacher)
- 📊 **Dashboard ส่วนตัว**: แสดงสถิติคอร์ส จำนวนนักเรียน และข้อมูลการสอน
- 📚 **จัดการคอร์สเรียน**: สร้าง แก้ไข ลบ คอร์สเรียน พร้อมอัพโหลดรูปปก
- 📂 **จัดการโมดูล**: จัดระเบียบเนื้อหาเป็นโมดูล พร้อมกำหนดลำดับ
- 📝 **จัดการบทเรียน**: สร้างบทเรียนรองรับหลายรูปแบบ (PDF, Video, Text, Google Drive, Canva)
- 🎯 **สร้างแบบทดสอบ**: สร้างข้อสอบพร้อมตัวเลือกคำตอบ กำหนดเวลาและคะแนนผ่าน
- 👥 **ดูรายชื่อนักเรียน**: ตรวจสอบนักเรียนที่ลงทะเบียนเรียนในแต่ละคอร์ส
- 📈 **ดูสถิติการเรียน**: ติดตามความคืบหน้าและผลการเรียนของนักเรียน

### 👨‍🎓 สำหรับนักเรียน (Student)
- 📚 **Dashboard ส่วนตัว**: แสดงคอร์สที่ลงทะเบียนพร้อม Progress Bar
- 📖 **เรียนบทเรียนออนไลน์**: เข้าถึงเนื้อหาทุกรูปแบบ (PDF, Video, Text, Google Drive, Canva)
- ✅ **บันทึกความคืบหน้า**: Mark Complete และติดตาม Progress แบบ Real-time
- 📝 **ทำแบบทดสอบ**: ทำ Quiz พร้อม Timer และดูผลคะแนนทันที
- 🎓 **ขอใบประกาศนียบัตร**: รับ Certificate PDF เมื่อเรียนจบคอร์ส
- 🏆 **ดูผลงาน**: ดูใบประกาศนียบัตรและประวัติการเรียนทั้งหมด

### 🔧 สำหรับผู้ดูแลระบบ (Admin)
- 👥 **จัดการผู้ใช้**: สร้าง แก้ไข ลบ ผู้ใช้ทั้งหมด
- 🔄 **เปลี่ยนบทบาท**: กำหนดและเปลี่ยน Role ของผู้ใช้
- 📊 **ดูสถิติระบบ**: สถิติการใช้งาน คอร์ส และผู้ใช้ทั้งหมด
- 📚 **จัดการคอร์ส**: ดูและจัดการคอร์สเรียนทั้งหมดในระบบ
- 📈 **รายงาน**: สร้างรายงานต่างๆ สำหรับการบริหาร

---

## 🛠️ เทคโนโลยีที่ใช้

### Backend
- **Framework**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage System
- **PDF Generation**: DomPDF

### Frontend
- **Template Engine**: Blade Templates
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js (Lightweight)
- **Icons**: Font Awesome
- **Build Tool**: Vite

### Development Tools
- **Package Manager**: Composer & NPM
- **Version Control**: Git
- **Testing**: PHPUnit (แผน)

---

## 📦 การติดตั้ง

### Requirements
- **PHP** >= 8.1
- **Composer** (PHP Package Manager)
- **MySQL** >= 8.0
- **Node.js** & **NPM** (JavaScript Runtime & Package Manager)
- **Git** (Version Control)

### ขั้นตอนการติดตั้ง

#### 1. Clone โปรเจค
```bash
git clone https://github.com/yourusername/ct-learning.git
cd ct-learning
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

#### 3. Setup Environment
```bash
# คัดลอกไฟล์ environment
cp .env.example .env

# สร้าง application key
php artisan key:generate
```

#### 4. ตั้งค่า Database
แก้ไขไฟล์ `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 5. Run Migration & Seed
```bash
# สร้างตารางและข้อมูลตัวอย่าง
php artisan migrate:fresh --seed
```

#### 6. Setup Storage Link
```bash
# สร้าง symbolic link สำหรับ file storage
php artisan storage:link
```

#### 7. Compile Assets
```bash
# สำหรับ development
npm run dev

# สำหรับ production
npm run build
```

#### 8. Start Development Server
```bash
php artisan serve
```

เปิดเว็บที่: `http://localhost:8000`

---

## 🔐 บัญชีทดสอบ

| Role | Email | Password | คำอธิบาย |
|------|-------|----------|-----------|
| **Admin** | admin@ct.ac.th | password | ผู้ดูแลระบบ |
| **Teacher** | teacher1@ct.ac.th | password | ครูผู้สอนคนที่ 1 |
| **Teacher** | teacher2@ct.ac.th | password | ครูผู้สอนคนที่ 2 |
| **Student** | student1@ct.ac.th | password | นักเรียนคนที่ 1 |
| **Student** | student2@ct.ac.th | password | นักเรียนคนที่ 2 |
| **Student** | student3@ct.ac.th | password | นักเรียนคนที่ 3 |

---

## 📁 โครงสร้างโปรเจค

```
ct-learning/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin Controllers
│   │   │   ├── Teacher/        # Teacher Controllers
│   │   │   └── Student/        # Student Controllers
│   │   └── Middleware/         # Role-based Middleware
│   └── Models/                 # Eloquent Models
├── database/
│   ├── migrations/             # Database Migrations
│   └── seeders/                # Database Seeders
├── resources/
│   └── views/                  # Blade Templates
│       ├── admin/              # Admin Views
│       ├── teacher/            # Teacher Views
│       ├── student/            # Student Views
│       └── layouts/            # Layout Components
├── routes/
│   ├── web.php                 # Web Routes
│   └── auth.php                # Authentication Routes
├── storage/
│   └── app/public/             # File Storage
├── public/
│   └── storage -> ../storage/app/public  # Symlink
└── context/
    └── docs/                   # 📚 เอกสารทั้งหมด
```

---

## 🔄 Workflow การทำงาน

### Student Workflow
```
Register → Login → Browse Courses → Enroll → 
Learn Lessons → Mark Complete → Take Quiz → 
Get Certificate
```

### Teacher Workflow
```
Login → Create Course → Add Modules → 
Add Lessons → Upload Content → Create Quiz → 
Add Questions → View Student Progress
```

### Admin Workflow
```
Login → Manage Users → View Statistics → 
Manage Courses → System Administration
```

---

## 🗄️ Database Schema

### Main Tables
- **users** - ผู้ใช้ทั้งหมด (Student, Teacher, Admin)
- **courses** - คอร์สเรียน
- **enrollments** - การลงทะเบียนเรียน
- **modules** - บทเรียน/โมดูล
- **lessons** - เนื้อหาบทเรียน
- **lesson_completions** - บันทึกการเรียนเสร็จ
- **quizzes** - แบบทดสอบ
- **questions** - คำถาม
- **answers** - คำตอบ
- **quiz_attempts** - ผลการทำแบบทดสอบ
- **certificates** - ใบประกาศนียบัตร

### ความสัมพันธ์หลัก
```
users (1:N) courses (teacher_id)
users (N:M) courses (enrollments)
courses (1:N) modules
modules (1:N) lessons
lessons (1:N) lesson_completions
modules (1:N) quizzes
quizzes (1:N) questions
questions (1:N) answers
quizzes (1:N) quiz_attempts
```

---

## 📚 เอกสารประกอบ

เอกสารครบถ้วนอยู่ใน folder `context/docs/`:

### 📅 บันทึกการพัฒนา (Development Logs)
1. **[Day 1 Complete - Authentication & Roles](./context/docs/DAY1-COMPLETE.md)**
   - ระบบยืนยันตัวตนและสิทธิ์ผู้ใช้
   - Admin Panel สำหรับจัดการผู้ใช้
   - Role-based middleware

2. **[Day 2 Complete - Course Management](./context/docs/DAY2-COMPLETE.md)**
   - ระบบจัดการคอร์สเรียนสำหรับครูผู้สอน
   - Image upload พร้อม preview
   - CRUD operations ครบถ้วน

3. **[Day 3 Complete - Module & Lesson Management](./context/docs/DAY3-COMPLETE.md)**
   - ระบบจัดการบทเรียนและเนื้อหา
   - Rich text editor (Quill.js)
   - Video upload และ multiple content types

4. **[Day 4 Complete - Quiz System & Certificate](./context/docs/DAY4-COMPLETE.md)**
   - ระบบแบบทดสอบพร้อม timer และ auto-grading
   - ระบบออกใบประกาศนียบัตร PDF
   - Progress tracking และ analytics

### 📚 คู่มือระบบ (System Guides)
5. **[LMS Complete Guide](./context/docs/LMS-COMPLETE-GUIDE.md)**
   - คู่มือครบถ้วนทุกระบบ
   - วิธีแก้ไขและปรับแต่ง
   - Troubleshooting

6. **[Quiz System Guide](./context/docs/QUIZ-SYSTEM-GUIDE.md)**
   - ระบบแบบทดสอบแบบละเอียด
   - การคำนวณคะแนน
   - การปรับแต่งระบบ Quiz

7. **[Certificate System Guide](./context/docs/CERTIFICATE-SYSTEM-GUIDE.md)**
   - ระบบออกใบประกาศนียบัตร
   - การสร้าง PDF
   - การปรับแต่ง Template

8. **[Quick Reference](./context/docs/QUICK-REFERENCE.md)**
   - คู่มือใช้งานด่วน
   - คำสั่ง Artisan
   - การแก้ไขไฟล์

### 🔧 ข้อมูลอ้างอิง (Reference)
- **[Documentation Index](./context/docs/INDEX.md)** - ดูเอกสารทั้งหมด
- **[Architecture Documentation](./context/docs/ARCHITECTURE.md)** - สถาปัตยกรรมระบบ
- **[Routes Reference](./context/docs/ROUTES-REFERENCE.md)** - รายการ Routes ทั้งหมด

---

## 🎨 UI/UX Features

- ✅ **Responsive Design**: รองรับทุกอุปกรณ์ (Mobile, Tablet, Desktop)
- ✅ **Dark Mode Support**: สลับโหมดมืด/สว่างได้
- ✅ **Modern UI**: ใช้ Tailwind CSS พร้อม Glass Morphism design
- ✅ **Interactive Components**: Hover effects, transitions, micro-interactions
- ✅ **Progress Visualization**: Progress bars และ completion badges
- ✅ **Accessibility**: รองรับการเข้าถึงสำหรับผู้พิการ
- ✅ **Fast Loading**: Optimized assets และ lazy loading

---

## 🔒 Security Features

- ✅ **Authentication**: Laravel Breeze พร้อม email verification
- ✅ **Authorization**: Role-based access control (Middleware)
- ✅ **CSRF Protection**: ป้องกัน Cross-Site Request Forgery
- ✅ **SQL Injection Prevention**: ผ่าน Eloquent ORM
- ✅ **XSS Protection**: Auto-escaping ใน Blade templates
- ✅ **Password Hashing**: bcrypt encryption
- ✅ **File Upload Security**: ตรวจสอบประเภทและขนาดไฟล์
- ✅ **Input Validation**: Form validation ทุกที่

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter TestName

# Run test with coverage
php artisan test --coverage
```

---

## 📈 Performance & Optimization

### Database Optimization
- **Eager Loading**: ป้องกัน N+1 query problems
- **Database Indexing**: Index บน foreign keys และฟิลด์ที่ใช้ค้นหา
- **Query Caching**: Cache ผลลัพธ์ที่ใช้บ่อย

### Frontend Optimization
- **Asset Minification**: CSS และ JavaScript minification
- **Image Optimization**: Lazy loading และ responsive images
- **Caching**: Browser caching และ CDN (แผน)

---

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` ใน `.env`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Run `php artisan migrate --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Set file permissions: `chmod -R 775 storage bootstrap/cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Build assets: `npm run build`
- [ ] Setup SSL certificate (HTTPS)
- [ ] Configure backup system

---

## 📈 Future Features (Roadmap)

### Phase 1: Enhanced Learning Experience
- [ ] **Discussion Forums**: กระดานสนทนาสำหรับแต่ละคอร์ส
- [ ] **Live Chat**: แชทสดระหว่างครูและนักเรียน
- [ ] **Video Conference Integration**: Zoom/Google Meet integration
- [ ] **Assignment System**: ระบบส่งงานและตรวจสอบ

### Phase 2: Gamification & Engagement
- [ ] **Points System**: ระบบสะสมคะแนน
- [ ] **Badges & Achievements**: ระบบเหรียญและความสำเร็จ
- [ ] **Leaderboards**: ตารางอันดับ
- [ ] **Learning Paths**: แนวทางการเรียนแบบกำหนดเอง

### Phase 3: Advanced Features
- [ ] **Mobile App**: Flutter/React Native application
- [ ] **Advanced Analytics**: Learning analytics dashboard
- [ ] **AI Recommendations**: ระบบแนะนำคอร์สเรียน
- [ ] **Multi-language Support**: รองรับหลายภาษา
- [ ] **Payment Integration**: ระบบชำระเงินสำหรับคอร์สพรีเมียม

---

## 🐛 Bug Reports & Support

หากพบปัญหาหรือต้องการรายงานข้อผิดพลาด:
- **GitHub Issues**: [สร้าง Issue ใหม่](https://github.com/yourusername/ct-learning/issues)
- **Email**: support@ct.ac.th
- **Line**: @ct-learning

### ปัญหาที่พบบ่อยและวิธีแก้ไข
ดูเพิ่มเติมใน [Troubleshooting Guide](./context/docs/TROUBLESHOOTING.md)

---

## 👥 Contributors

- **Lead Developer**: [Pchan132](https://github.com/pchan132) - Full-stack Development
- **UI/UX Designer**: [Designer Name] - Interface Design
- **Project Advisor**: [Advisor Name] - Project Guidance
- **Testers**: [Team Members] - Quality Assurance

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

ขอขอบคุณ:
- **Laravel Framework** - PHP framework ที่ยอดเยี่ยม
- **Tailwind CSS** - Utility-first CSS framework
- **Laravel Breeze** - Authentication scaffolding
- **Font Awesome** - Icon library
- **DomPDF** - PDF generation library
- **Alpine.js** - Lightweight JavaScript framework
- **ทีมนักพัฒนา** - ทุกคนที่มีส่วนร่วมในโปรเจคนี้

---

## 📞 Contact Information

- **Website**: https://ct-learning.com
- **Email**: admin@ct.ac.th
- **Facebook**: CT Learning Official
- **GitHub**: https://github.com/yourusername/ct-learning
- **Repository**: [Project-CT-Learning](https://github.com/pchan132/Project-CT-Learning)

---

## 📈 สถานะการพัฒนา

### ✅ Days Completed (4/4)
- **Day 1**: ✅ Authentication & Roles - สมบูรณ์
- **Day 2**: ✅ Course Management - สมบูรณ์
- **Day 3**: ✅ Module & Lesson Management - สมบูรณ์
- **Day 4**: ✅ Quiz System & Certificate - สมบูรณ์

### 🎯 ระบบที่พร้อมใช้งาน
- ✅ ระบบ Authentication และ Role-based Access Control
- ✅ ระบบจัดการคอร์สเรียน (Teacher CRUD + Image Upload)
- ✅ ระบบจัดการ Modules และ Lessons (Multi-format Content)
- ✅ ระบบแบบทดสอบพร้อม Timer, Auto-grading, Progress Tracking
- ✅ ระบบออกใบประกาศนียบัตร PDF อัตโนมัติ
- ✅ ระบบ Student Learning และ Progress Tracking
- ✅ Admin Panel สำหรับจัดการผู้ใช้และสถิติ
- ✅ UI/UX สวยงามพร้อม Dark Mode และ Responsive Design
- ✅ File Storage System พร้อม Security
- ✅ Database Design ที่ดีและ Scalable

### 🚀 Production Status: **READY**
ระบบ LMS ครบถ้วนพร้อมใช้งานจริงในสภาพแวดล้อม Production

---

**สร้างเมื่อ**: พฤศจิกายน 2025  
**Version**: 1.0.0 (Complete)  
**Status**: ✅ Production Ready  
**Development Days**: 4/4 Completed  
**Last Updated**: 2025-11-28

---

<p align="center">
  <strong>🚀 CT Learning - Building the Future of Online Education 🚀</strong><br>
  Made with ❤️ using Laravel, Tailwind CSS & Modern Web Technologies<br>
  <em>Empowering Education Through Technology</em>
</p>