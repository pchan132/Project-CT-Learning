# 🎓 LMS สำหรับแผนกเทคโนโลยีคอมพิวเตอร์

ระบบ Learning Management System (LMS) สำหรับจัดการการเรียนการสอนออนไลน์

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)

---

## 📋 ภาพรวมระบบ

ระบบ LMS นี้พัฒนาขึ้นเพื่อใช้ในการจัดการเรียนการสอนออนไลน์สำหรับแผนกเทคโนโลยีคอมพิวเตอร์ ประกอบด้วยฟีเจอร์หลัก:

- ✅ ระบบ Authentication + Roles (Student, Teacher, Admin)
- ✅ ระบบจัดการคอร์สเรียน (CRUD)
- ✅ ระบบลงทะเบียนเรียน (Enrollment)
- ✅ ระบบจัดการบทเรียน (Modules & Lessons)
- ✅ รองรับเนื้อหาหลายประเภท (Text, Video, PDF, PPT)
- ✅ ระบบติดตาม Progress การเรียน
- ✅ ระบบแบบทดสอบ (Quiz) พร้อมคำนวณคะแนน
- ✅ ระบบออกใบประกาศนียบัตร (Certificate PDF)
- ✅ ระบบจัดการผู้ใช้ (Admin Panel)
- ✅ Dashboard และสถิติ

---

## 🚀 ฟีเจอร์

### สำหรับ Student:
- 📚 ดูคอร์สทั้งหมดและลงทะเบียนเรียน
- 📖 เรียนบทเรียนออนไลน์ (Text, Video, PDF, PPT)
- ✅ Mark Complete และติดตาม Progress
- 📝 ทำแบบทดสอบและดูผลคะแนน
- 🎓 ขอใบประกาศนียบัตรเมื่อจบคอร์ส

### สำหรับ Teacher:
- ➕ สร้างและจัดการคอร์ส
- 📂 เพิ่ม Module และ Lesson
- 📤 Upload ไฟล์ PDF/PPT
- 🎯 สร้างแบบทดสอบพร้อมคำถาม
- 📊 ดูผลการเรียนของนักเรียน

### สำหรับ Admin:
- 👥 จัดการผู้ใช้ (Create, Edit, Delete)
- 📊 ดูสถิติและรายงาน
- 🔧 จัดการระบบโดยรวม

---

## 🛠️ เทคโนโลยีที่ใช้

- **Backend:** Laravel 10 (PHP 8.1+)
- **Database:** MySQL 8.0
- **Frontend:** Blade Templates, Tailwind CSS
- **Authentication:** Laravel Breeze
- **PDF Generation:** DomPDF
- **File Storage:** Laravel Storage

---

## 📦 การติดตั้ง

### Requirements:
- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM

### ขั้นตอนการติดตั้ง:

```bash
# 1. Clone โปรเจค
git clone https://github.com/yourusername/ct-learning.git
cd ct-learning

# 2. Install Dependencies
composer install
npm install

# 3. Setup Environment
cp .env.example .env
php artisan key:generate

# 4. Config Database ใน .env
DB_DATABASE=ct_learning
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Run Migration + Seed
php artisan migrate:fresh --seed

# 6. Create Storage Link
php artisan storage:link

# 7. Compile Assets
npm run dev

# 8. Run Server
php artisan serve
```

เปิดเว็บที่: `http://localhost:8000`

---

## 🔐 บัญชีทดสอบ

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@ct.ac.th | password |
| **Teacher** | teacher1@ct.ac.th | password |
| **Student** | student1@ct.ac.th | password |

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
│   │   └── Middleware/         # Middleware (Role-based)
│   └── Models/                 # Eloquent Models
├── database/
│   ├── migrations/             # Database Migrations
│   └── seeders/                # Database Seeders
├── resources/
│   └── views/                  # Blade Templates
├── routes/
│   └── web.php                 # Web Routes
└── context/
    └── docs/                   # 📚 เอกสารทั้งหมด
        ├── LMS-COMPLETE-GUIDE.md
        ├── QUIZ-SYSTEM-GUIDE.md
        ├── CERTIFICATE-SYSTEM-GUIDE.md
        └── QUICK-REFERENCE.md
```

---

## 📚 เอกสารประกอบ

เอกสารครบถ้วนอยู่ใน folder `context/docs/`:

1. **[LMS-COMPLETE-GUIDE.md](./context/docs/LMS-COMPLETE-GUIDE.md)**
   - คู่มือครบถ้วนทุกระบบ
   - วิธีแก้ไขและปรับแต่ง
   - Troubleshooting

2. **[QUIZ-SYSTEM-GUIDE.md](./context/docs/QUIZ-SYSTEM-GUIDE.md)**
   - ระบบแบบทดสอบแบบละเอียด
   - การคำนวณคะแนน
   - การปรับแต่งระบบ Quiz

3. **[CERTIFICATE-SYSTEM-GUIDE.md](./context/docs/CERTIFICATE-SYSTEM-GUIDE.md)**
   - ระบบออกใบประกาศนียบัตร
   - การสร้าง PDF
   - การปรับแต่ง Template

4. **[QUICK-REFERENCE.md](./context/docs/QUICK-REFERENCE.md)**
   - คู่มือใช้งานด่วน
   - คำสั่ง Artisan
   - การแก้ไขไฟล์

---

## 🔄 Workflow

### Student Workflow:
```
Register → Login → Browse Courses → Enroll → 
Learn Lessons → Mark Complete → Take Quiz → 
Get Certificate
```

### Teacher Workflow:
```
Login → Create Course → Add Modules → 
Add Lessons → Upload Content → Create Quiz → 
Add Questions → View Student Progress
```

### Admin Workflow:
```
Login → Manage Users → View Statistics → 
Approve/Delete Content
```

---

## 🗄️ Database Schema

### Main Tables:
- `users` - ผู้ใช้ทั้งหมด (Student, Teacher, Admin)
- `courses` - คอร์สเรียน
- `enrollments` - การลงทะเบียนเรียน
- `modules` - บทเรียน
- `lessons` - เนื้อหาบทเรียน
- `lesson_completions` - บันทึกการเรียนเสร็จ
- `quizzes` - แบบทดสอบ
- `questions` - คำถาม
- `answers` - คำตอบ
- `quiz_attempts` - ผลการทำแบบทดสอบ
- `certificates` - ใบประกาศนียบัตร

---

## 🎨 UI/UX

- ✅ Responsive Design (รองรับทุกอุปกรณ์)
- ✅ Dark Mode Support (ถ้ามีการติดตั้ง)
- ✅ ใช้งานง่าย เข้าใจได้ทันที
- ✅ แสดงผล Progress แบบ Real-time

---

## 🔒 Security

- ✅ Authentication with Laravel Breeze
- ✅ Role-based Authorization (Middleware)
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection
- ✅ Password Hashing (bcrypt)

---

## 🧪 Testing

```bash
# Run Tests
php artisan test

# Run Specific Test
php artisan test --filter TestName
```

---

## 📈 Future Features (Coming Soon)

- [ ] ระบบ Discussion/Forum สำหรับแต่ละคอร์ส
- [ ] ระบบ Notification (Email/In-app)
- [ ] ระบบ Rating/Review คอร์ส
- [ ] ระบบ Live Chat
- [ ] ระบบ Video Conference Integration
- [ ] Mobile App (Flutter/React Native)
- [ ] Advanced Analytics Dashboard
- [ ] Gamification (Badges, Points, Leaderboard)

---

## 🐛 Bug Reports

พบปัญหา? สร้าง Issue ใน GitHub หรือติดต่อ:
- Email: support@ct.ac.th
- Line: @ct-learning

---

## 👥 Contributors

- **Developer:** [Your Name]
- **Designer:** [Designer Name]
- **Advisor:** [Advisor Name]

---

## 📄 License

This project is licensed under the MIT License.

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- DomPDF
- และทุก Package ที่ใช้ในโปรเจค

---

## 📞 Contact

- **Website:** https://ct-learning.com
- **Email:** admin@ct.ac.th
- **Facebook:** CT Learning
- **GitHub:** https://github.com/yourusername/ct-learning

---

**สร้างเมื่อ:** พฤศจิกายน 2025  
**Version:** 1.0.0  
**Status:** ✅ Production Ready
