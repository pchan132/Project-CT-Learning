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

Dashboard routes มี middleware protection:
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/dashboard', ...);
    Route::get('/teacher/dashboard', ...);
});
```

- `auth` - ต้องล็อกอินก่อน
- `verified` - ต้องยืนยัน email (ถ้าเปิดใช้งาน)

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