# Authentication System Documentation

## ภาพรวมระบบ Authentication

ระบบ Authentication ของ CT-Learning รองรับการลงทะเบียนและการเข้าสู่ระบบสำหรับผู้ใช้ 2 ประเภท:
- **Student (นักศึกษา)** - สามารถเข้าถึง Student Dashboard
- **Teacher (อาจารย์)** - สามารถเข้าถึง Teacher Dashboard

## โครงสร้างระบบ

### 1. User Model
**ไฟล์**: [`app/Models/User.php`](../../app/Models/User.php)

```php
protected $fillable = [
    'name',
    'email', 
    'password',
    'role', // เพิ่มเพื่อรองรับการบันทึก role ของผู้ใช้
];

// Helper methods
public function isStudent(): bool {
    return $this->role === 'student';
}

public function isTeacher(): bool {
    return $this->role === 'teacher';
}
```

### 2. Routes
**ไฟล์**: [`routes/web.php`](../../routes/web.php)

#### Registration Routes
```
GET  /register/student     → register.student        → RegisteredUserController@createStudent
POST /register/student     → register.student.store  → RegisteredUserController@storeStudent
GET  /register/teacher     → register.teacher        → RegisteredUserController@createTeacher  
POST /register/teacher     → register.teacher.store  → RegisteredUserController@storeTeacher
```

#### Dashboard Routes
```
GET /student/dashboard → student.dashboard
GET /teacher/dashboard → teacher.dashboard
```

### 3. Controllers
**ไฟล์**: [`app/Http/Controllers/Auth/RegisteredUserController.php`](../../app/Http/Controllers/Auth/RegisteredUserController.php)

#### Methods หลัก:
- `createStudent()` - แสดงฟอร์มลงทะเบียนนักศึกษา
- `storeStudent()` - บันทึกข้อมูลนักศึกษา และ redirect ไป student dashboard
- `createTeacher()` - แสดงฟอร์มลงทะเบียนอาจารย์
- `storeTeacher()` - บันทึกข้อมูลอาจารย์ และ redirect ไป teacher dashboard

### 4. Middleware
**ไฟล์**: [`app/Http/Middleware/RedirectIfAuthenticated.php`](../../app/Http/Middleware/RedirectIfAuthenticated.php)

ทำหน้าที่ตรวจสอบ role ของผู้ใช้ที่ล็อกอินอยู่ และ redirect ไปยัง dashboard ที่เหมาะสม:
- Student → `/student/dashboard`
- Teacher → `/teacher/dashboard`

### 5. Views
**ไฟล์**: [`resources/views/auth/register.blade.php`](../../resources/views/auth/register.blade.php)

ฟอร์มลงทะเบียนที่รองรับ:
- Dynamic form action ตาม role parameter
- Role dropdown ที่ lock ตามประเภทการลงทะเบียน
- Hidden field สำหรับส่งค่า role

## วิธีการใช้งาน

### การลงทะเบียน

#### 1. ลงทะเบียนนักศึกษา
```
URL: /register/student
Method: GET/POST
Flow: 
1. แสดงฟอร์มพร้อม role = "student" (lock)
2. ผู้ใช้กรอกข้อมูล
3. บันทึกลงฐานข้อมูล (role = 'student')
4. Login อัตโนมัติ
5. Redirect ไป /student/dashboard
```

#### 2. ลงทะเบียนอาจารย์
```
URL: /register/teacher
Method: GET/POST
Flow:
1. แสดงฟอร์มพร้อม role = "teacher" (lock)
2. ผู้ใช้กรอกข้อมูล  
3. บันทึกลงฐานข้อมูล (role = 'teacher')
4. Login อัตโนมัติ
5. Redirect ไป /teacher/dashboard
```

### การเข้าสู่ระบบ (Login)

```
URL: /login
Method: POST
Flow:
1. ผู้ใช้กรอก email และ password
2. Laravel Auth ตรวจสอบข้อมูล
3. RedirectIfAuthenticated middleware ตรวจสอบ role
4. Redirect ตาม role:
   - Student → /student/dashboard
   - Teacher → /teacher/dashboard
```

## การป้องกัน Route

### Middleware Registration
**ไฟล์**: [`app/Http/Kernel.php`](../../app/Http/Kernel.php)

เพิ่ม middleware aliases ใน `$middlewareAliases`:
```php
protected $middlewareAliases = [
    // ... existing middleware
    'student' => \App\Http\Middleware\StudentMiddleware::class,
    'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
    // ... other middleware
];
```

### Route Protection
Dashboard routes มี middleware protection:
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard')->middleware('student');
    
    Route::get('/teacher/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard')->middleware('teacher');
});
```

**Middleware Stack:**
- `auth` - ต้องล็อกอินก่อน
- `verified` - ต้องยืนยัน email (ถ้าเปิดใช้งาน)
- `student` - อนุญาตเฉพาะ student (สำหรับ student dashboard)
- `teacher` - อนุญาตเฉพาะ teacher (สำหรับ teacher dashboard)

### การทำงานของ Role-Based Middleware

#### ทำไมต้องสร้าง Middleware แยก?

1. **Single Responsibility** - แต่ละ middleware ทำหน้าที่เดียวชัดเจน
2. **Reusability** - สามารถนำไปใช้กับ route อื่นๆ ได้
3. **Maintainability** - แก้ไขง่ายเมื่อมีการเปลี่ยนแปลง business logic
4. **Security** - ชั้นการป้องกันที่ชัดเจนและสมบูรณ์

#### การ Redirect ตาม Role

**StudentMiddleware:**
- ถ้า user ไม่ใช่ student → redirect ไป dashboard ที่เหมาะสม
- Student → อนุญาตให้ผ่านไปได้
- Teacher → redirect ไป `/teacher/dashboard`
- Role อื่น → redirect ไป `/dashboard`

**TeacherMiddleware:**
- ถ้า user ไม่ใช่ teacher → redirect ไป dashboard ที่เหมาะสม
- Teacher → อนุญาตให้ผ่านไปได้
- Student → redirect ไป `/student/dashboard`
- Role อื่น → redirect ไป `/dashboard`

## Database Schema

### Users Table
```sql
- id (bigint, primary key)
- name (string, 255)
- email (string, 255, unique)
- email_verified_at (timestamp, nullable)
- password (string, 255)
- role (string, 255) ← เพิ่มใหม่
- remember_token (string, 100, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## Security Features

1. **Password Hashing** - ใช้ Laravel's built-in password hashing
2. **CSRF Protection** - ทุกฟอร์มมี CSRF token
3. **Input Validation** - ตรวจสอบข้อมูลที่ส่งมา
4. **Email Verification** - รองรับการยืนยัน email (optional)
5. **Rate Limiting** - Laravel's built-in rate limiting

## Error Handling

### Common Errors และวิธีแก้ไข:

1. **Route not defined**
   - ตรวจสอบว่ามี route ใน `routes/web.php`
   - รัน `php artisan route:list` เพื่อตรวจสอบ

2. **Role not saved**
   - ตรวจสอบว่า 'role' อยู่ใน `$fillable` ของ User model
   - ตรวจสอบ database migration

3. **Redirect loop**
   - ตรวจสอสอบ RedirectIfAuthenticated middleware
   - ตรวจสอบว่า route ไม่มี middleware ซ้ำซ้อน

## Testing Commands

### ตรวจสอบ Routes
```bash
php artisan route:list --name=student
php artisan route:list --name=teacher
php artisan route:list --name=register
```

### Clear Cache
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Migration
```bash
php artisan migrate:fresh --seed
```

## Future Enhancements

1. **Email Verification** - เปิดใช้งานการยืนยัน email
2. **Social Login** - เพิ่มการล็อกอินผ่าน social media
3. **Two-Factor Authentication** - เพิ่มความปลอดภัยด้วย 2FA
4. **Role Management** - พัฒนาระบบจัดการสิทธิ์ที่ซับซ้อนขึ้น
5. **API Authentication** - เพิ่มการ auth สำหรับ API

---

**Last Updated**: 2025-11-19  
**Version**: 1.0.0  
**Maintainer**: CT-Learning Development Team

## Testing Commands (เพิ่มเติม)

### ตรวจสอบ Routes และ Middleware
```bash
# ดู routes ทั้งหมด
php artisan route:list

# กรอง routes ตามชื่อ
php artisan route:list --name=dashboard
php artisan route:list --name=student
php artisan route:list --name=teacher
php artisan route:list --name=register

# ตรวจสอบ routes ตาม path
php artisan route:list --path=student/dashboard
php artisan route:list --path=teacher/dashboard

# ดู middleware ที่ใช้กับแต่ละ route
php artisan route:list --middleware=student
php artisan route:list --middleware=teacher
```

### ทดสอบ Middleware
```bash
# Clear cache ทั้งหมด (ทำหลังจากแก้ไข middleware หรือ routes)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# หรือใช้คำสั่งรวม
php artisan optimize:clear

# ตรวจสอบว่า middleware ถูกลงทะเบียนอย่างถูกต้อง
php artisan route:list | findstr "student"
php artisan route:list | findstr "teacher"
```

### ทดสอบการทำงานจริง (Manual Testing)

#### 1. ทดสอบการลงทะเบียน
```bash
# เปิด browser แล้วไปที่:
http://127.0.0.1:8000/register/student
http://127.0.0.1:8000/register/teacher
```

**ตรวจสอบ:**
- ฟอร์มแสดง role ที่ถูกต้องและ lock อยู่
- หลังลงทะเบียน redirect ไป dashboard ที่ถูกต้อง
- ข้อมูลในฐานข้อมูลมี role ที่ถูกต้อง

#### 2. ทดสอบการ Login
```bash
# เปิด browser แล้วไปที่:
http://127.0.0.1:8000/login
```

**ตรวจสอบ:**
- Login สำเร็จแล้ว redirect ไป dashboard ตาม role
- Student → `/student/dashboard`
- Teacher → `/teacher/dashboard`

#### 3. ทดสอบการป้องกัน Route
```bash
# ทดสอบโดยการพิมพ์ URL ตรง:
http://127.0.0.1:8000/student/dashboard  (ขณะ login ด้วย teacher)
http://127.0.0.1:8000/teacher/dashboard  (ขณะ login ด้วย student)
```

**ตรวจสอบ:**
- Teacher พยายามเข้า student dashboard → redirect ไป teacher dashboard
- Student พยายามเข้า teacher dashboard → redirect ไป student dashboard
- ผู้ใช้ที่ไม่ได้ login → redirect ไปหน้า login

### ทดสอบผ่าน Command Line
```bash
# สร้าง user สำหรับทดสอบ
php artisan tinker
>>> User::create(['name' => 'Test Student', 'email' => 'student@test.com', 'password' => Hash::make('password'), 'role' => 'student']);
>>> User::create(['name' => 'Test Teacher', 'email' => 'teacher@test.com', 'password' => Hash::make('password'), 'role' => 'teacher']);
>>> exit

# ตรวจสอบว่า user ถูกสร้างแล้ว
php artisan tinker
>>> $student = User::where('email', 'student@test.com')->first();
>>> $student->isStudent(); // ควร return true
>>> $student->isTeacher(); // ควร return false
>>> exit
```

## การนำไปประยุกต์ใช้กับโปรเจคอื่น

### ขั้นตอนการนำไปใช้:

1. **เพิ่มฟิลด์ role ใน users table**
   ```sql
   ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user';
   ```

2. **อัพเดท User Model**
   ```php
   protected $fillable = ['name', 'email', 'password', 'role'];
   
   public function isAdmin(): bool {
       return $this->role === 'admin';
   }
   
   public function isUser(): bool {
       return $this->role === 'user';
   }
   ```

3. **สร้าง Middleware**
   ```bash
   php artisan make:middleware AdminMiddleware
   php artisan make:middleware UserMiddleware
   ```

4. **ลงทะเบียน Middleware ใน Kernel.php**
   ```php
   'admin' => \App\Http\Middleware\AdminMiddleware::class,
   'user' => \App\Http\Middleware\UserMiddleware::class,
   ```

5. **ใช้กับ Routes**
   ```php
   Route::get('/admin/dashboard', ...)->middleware('admin');
   Route::get('/user/profile', ...)->middleware('user');
   ```

### ปรับแต่งตามความต้องการ:
- เปลี่ยนชื่อ role (admin, moderator, member, etc.)
- เพิ่ม middleware สำหรับ permission ซับซ้อนขึ้น
- ใช้ Gate/Policy สำหรับการจัดการสิทธิ์ที่ซับซ้อน