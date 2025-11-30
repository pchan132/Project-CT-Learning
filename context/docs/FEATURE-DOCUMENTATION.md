# 🎯 CT Learning - Feature Documentation

## 📋 บทนำ

เอกสารนี้คือคำอธิบายฟีเจอร์ทั้งหมดของระบบ CT Learning อย่างละเอียด ครอบคลุมการทำงาน วิธีใช้งาน และข้อมูลทางเทคนิคสำหรับนักพัฒนาและผู้ดูแลระบบ

---

## 📋 สารบัญ

1. [Authentication System](#authentication-system)
2. [User Management](#user-management)
3. [Course Management](#course-management)
4. [Module & Lesson System](#module--lesson-system)
5. [Content Management](#content-management)
6. [Quiz System](#quiz-system)
7. [Progress Tracking](#progress-tracking)
8. [Certificate System](#certificate-system)
9. [File Upload System](#file-upload-system)
10. [Notification System](#notification-system)
11. [Admin Panel](#admin-panel)
12. [Reporting & Analytics](#reporting--analytics)

---

## 🔐 Authentication System

### ภาพรวม
ระบบยืนยันตัวตนแบบ Multi-role รองรับ Student, Teacher, และ Admin พร้อมความปลอดภัยขั้นสูง

### ฟีเจอร์หลัก

#### 1. User Registration
- **ประเภท**: Student Registration, Teacher Registration
- **Validation**: Email uniqueness, Password strength
- **Default Role**: Student (สามารถเปลี่ยนได้โดย Admin)
- **Email Verification**: ส่งอีเมลยืนยันตัวตน (เปิดใช้งานได้)

```php
// Registration Flow
POST /register/student → StudentMiddleware → StudentController@store
POST /register/teacher → TeacherMiddleware → TeacherController@store
```

#### 2. User Login
- **Methods**: Email + Password
- **Session Management**: Laravel Session
- **Remember Me**: 14 days cookie
- **Rate Limiting**: 5 attempts per 15 minutes

```php
// Login Flow
POST /login → AuthenticatedSessionController@store
```

#### 3. Password Reset
- **Email Verification**: ส่ง link ผ่านอีเมล
- **Token Expiration**: 60 minutes
- **Security**: ใช้ Laravel's built-in password reset

```php
// Password Reset Flow
POST /forgot-password → PasswordResetLinkController@store
POST /reset-password → NewPasswordController@store
```

#### 4. Role-Based Access Control
- **Middleware**: `StudentMiddleware`, `TeacherMiddleware`, `AdminMiddleware`
- **Route Protection**: กำหนด routes ตาม role
- **Permission Checks**: ตรวจสอบสิทธิ์ใน controller level

```php
// Middleware Examples
Route::middleware(['auth', 'student'])->group(function () {
    // Student routes
});

Route::middleware(['auth', 'teacher'])->group(function () {
    // Teacher routes
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

### Technical Details

#### User Model Structure
```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    // Role Methods
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

#### Security Features
- **Password Hashing**: bcrypt
- **CSRF Protection**: Built-in Laravel
- **Session Security**: Secure session configuration
- **Rate Limiting**: Login attempt throttling

---

## 👥 User Management

### ภาพรวม
ระบบจัดการผู้ใช้สำหรับ Admin และการจัดการโปรไฟล์ส่วนตัวสำหรับทุก role

### ฟีเจอร์หลัก

#### 1. Admin User Management
- **CRUD Operations**: สร้าง อ่าน แก้ไข ลบ ผู้ใช้
- **Bulk Operations**: ลบ/อัพเดทหลายรายการ
- **Search & Filter**: ค้นหาตามชื่อ อีเมล role
- **Role Assignment**: เปลี่ยน role ของผู้ใช้

```php
// Admin User Management Routes
GET /admin/users → AdminUserController@index
POST /admin/users → AdminUserController@store
GET /admin/users/{user}/edit → AdminUserController@edit
PUT /admin/users/{user} → AdminUserController@update
DELETE /admin/users/{user} → AdminUserController@destroy
```

#### 2. User Profile Management
- **Profile Information**: ชื่อ อีเมล รูปโปรไฟล์
- **Password Change**: เปลี่ยนรหัสผ่าน
- **Account Settings**: การแจ้งเตือน ภาษา ธีม
- **Activity Log**: ประวัติการใช้งานล่าสุด

```php
// Profile Management Routes
GET /profile → ProfileController@show
PUT /profile → ProfileController@update
PUT /profile/password → ProfileController@updatePassword
```

#### 3. Avatar System
- **Upload**: อัพโหลดรูปโปรไฟล์
- **Resizing**: สร้างรูปหลายขนาด (thumbnail, medium, large)
- **Default Avatar**: ใช้ UI Avatars ถ้าไม่มีรูป
- **Storage**: จัดเก็บใน `storage/app/public/avatars/`

### Technical Details

#### User Relationships
```php
class User extends Authenticatable
{
    // Student Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    public function lessonCompletions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Teacher Relationships
    public function coursesTaught()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }
}
```

#### Profile Update Validation
```php
class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(auth()->id())
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ]
        ];
    }
}
```

---

## 📚 Course Management

### ภาพรวม
ระบบจัดการคอร์สเรียนสำหรับครูผู้สอน รองรับการสร้าง แก้ไข ลบ และการจัดการนักเรียน

### ฟีเจอร์หลัก

#### 1. Course CRUD Operations
- **Create Course**: สร้างคอร์สใหม่พร้อมรายละเอียด
- **Edit Course**: แก้ไขข้อมูลคอร์ส
- **Delete Course**: ลบคอร์ส (พร้อม confirmation)
- **Course Listing**: แสดงรายการคอร์สทั้งหมดของครู

```php
// Course Management Routes
GET /teacher/courses → CourseController@index
POST /teacher/courses → CourseController@store
GET /teacher/courses/create → CourseController@create
GET /teacher/courses/{course}/edit → CourseController@edit
PUT /teacher/courses/{course} → CourseController@update
DELETE /teacher/courses/{course} → CourseController@destroy
```

#### 2. Course Cover Image
- **Upload**: อัพโหลดรูปปกคอร์ส
- **Image Processing**: Resize และ optimize
- **Multiple Sizes**: thumbnail (400x300), large (1200x800)
- **Storage**: `storage/app/public/courses/covers/`

#### 3. Course Enrollment Management
- **Student List**: ดูรายชื่อนักเรียนที่ลงทะเบียน
- **Enrollment Statistics**: จำนวนนักเรียน วันที่ลงทะเบียน
- **Bulk Operations**: ลบนักเรียน ส่งข้อความ
- **Progress Overview**: ดูความคืบหน้าโดยรวม

#### 4. Course Catalog (Student View)
- **Public Listing**: แสดงคอร์สที่เปิดให้ลงทะเบียน
- **Search & Filter**: ค้นหาตามชื่อ ครูผู้สอน หมวดหมู่
- **Course Details**: ดูข้อมูลครรับก่อนลงทะเบียน
- **Enrollment Button**: ปุ่มลงทะเบียน/ดูคอร์สที่ลงทะเบียนแล้ว

### Technical Details

#### Course Model Structure
```php
class Course extends Model
{
    protected $fillable = [
        'teacher_id', 'title', 'description', 'cover_image_url'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    // Computed Properties
    public function getTotalLessonsAttribute()
    {
        return $this->modules->sum(function ($module) {
            return $module->lessons->count();
        });
    }

    public function getEnrollmentsCountAttribute()
    {
        return $this->enrollments->count();
    }
}
```

#### Course Validation
```php
class StoreCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:5120' // 5MB
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'กรุณาระบุชื่อคอร์ส',
            'title.max' => 'ชื่อคอร์สต้องไม่เกิน 255 ตัวอักษร',
            'cover_image.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
        ];
    }
}
```

---

## 📖 Module & Lesson System

### ภาพรวม
ระบบจัดการเนื้อหาการสอนแบบ Nested Structure (Course → Modules → Lessons) รองรับหลายรูปแบบเนื้อหา

### ฟีเจอร์หลัก

#### 1. Module Management
- **Create Module**: สร้างโมดูลในคอร์ส
- **Edit Module**: แก้ไขข้อมูลโมดูล
- **Reorder Modules**: จัดลำดับโมดูล (drag & drop)
- **Module Statistics**: จำนวนบทเรียน ความคืบหน้า

```php
// Module Management Routes
GET /teacher/courses/{course}/modules → ModuleController@index
POST /teacher/courses/{course}/modules → ModuleController@store
PUT /teacher/modules/{module} → ModuleController@update
DELETE /teacher/modules/{module} → ModuleController@destroy
POST /teacher/modules/reorder → ModuleController@reorder
```

#### 2. Lesson Management
- **Create Lesson**: สร้างบทเรียนในโมดูล
- **Multiple Content Types**: PDF, Video, Text, Google Drive, Canva
- **Lesson Ordering**: จัดลำดับบทเรียน
- **Lesson Preview**: ดูตัวอย่างบทเรียน

```php
// Lesson Management Routes
GET /teacher/modules/{module}/lessons → LessonController@index
POST /teacher/modules/{module}/lessons → LessonController@store
PUT /teacher/lessons/{lesson} → LessonController@update
DELETE /teacher/lessons/{lesson} → LessonController@destroy
POST /teacher/lessons/reorder → LessonController@reorder
```

#### 3. Content Type Support
- **PDF Documents**: อัพโหลดไฟล์ PDF, DOC, PPT
- **Video Content**: YouTube video integration
- **Text Articles**: Rich text editor support
- **Google Drive**: Google Docs/Sheets/Slides embedding
- **Canva Designs**: Canva design embedding

#### 4. Lesson Learning Interface
- **Content Viewer**: แสดงเนื้อหาตามประเภท
- **Progress Tracking**: บันทึกการเรียนเสร็จ
- **Navigation**: ปุ่ม Previous/Next lesson
- **Completion Button**: ปุ่ม "Mark as Complete"

### Technical Details

#### Module Model Structure
```php
class Module extends Model
{
    protected $fillable = [
        'course_id', 'title', 'description', 'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    // Computed Properties
    public function getTotalLessonsAttribute()
    {
        return $this->lessons->count();
    }

    public function getCompletedLessonsAttribute($studentId)
    {
        return $this->lessons()
            ->whereHas('completions', function ($query) use ($studentId) {
                $query->where('user_id', $studentId);
            })
            ->count();
    }
}
```

#### Lesson Model Structure
```php
class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'content_type', 'content_url', 'content_text', 'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    // Content Types
    const CONTENT_TYPES = [
        'PDF' => 'PDF Document',
        'VIDEO' => 'Video Content',
        'TEXT' => 'Text Article',
        'GDRIVE' => 'Google Drive',
        'CANVA' => 'Canva Design'
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    // Content Processing Methods
    public function getProcessedContentAttribute()
    {
        switch ($this->content_type) {
            case 'VIDEO':
                return $this->convertYouTubeToEmbed();
            case 'GDRIVE':
                return $this->convertGoogleDriveToEmbed();
            case 'CANVA':
                return $this->convertCanvaToEmbed();
            default:
                return $this->content_url;
        }
    }

    private function convertYouTubeToEmbed()
    {
        $url = $this->content_url;
        
        // Convert youtube.com/watch?v=ID to youtube.com/embed/ID
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        
        // Convert youtu.be/ID to youtube.com/embed/ID
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        
        return $url;
    }
}
```

#### Lesson Completion Tracking
```php
class LessonCompletion extends Model
{
    protected $fillable = [
        'lesson_id', 'user_id', 'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    // Relationships
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static Methods
    public static function markComplete(Lesson $lesson, User $user)
    {
        return self::firstOrCreate([
            'lesson_id' => $lesson->id,
            'user_id' => $user->id
        ], [
            'completed_at' => now()
        ]);
    }
}
```

---

## 📝 Content Management

### ภาพรวม
ระบบจัดการเนื้อหาหลายรูปแบบ รองรับการอัพโหลดไฟล์ การฝังวิดีโอ และการแก้ไขข้อความ

### ฟีเจอร์หลัก

#### 1. File Upload System
- **Supported Formats**: PDF, DOC, DOCX, PPT, PPTX, JPG, PNG
- **File Size Limit**: 10MB per file
- **Security**: File type validation, virus scanning (future)
- **Storage**: Local storage with symbolic link

```php
// File Upload Handler
public function handleFileUpload(UploadedFile $file, string $type)
{
    $validatedData = $this->validateFile($file, $type);
    
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs("content/{$type}", $filename, 'public');
    
    return [
        'original_name' => $file->getClientOriginalName(),
        'filename' => $filename,
        'path' => $path,
        'url' => Storage::url($path),
        'size' => $file->getSize(),
        'mime_type' => $file->getMimeType()
    ];
}
```

#### 2. Rich Text Editor
- **Editor**: Quill.js (lightweight, customizable)
- **Features**: Bold, italic, underline, lists, links
- **Sanitization**: Strip dangerous HTML tags
- **Storage**: Store as HTML in database
- **Customization**: Thai font support, custom toolbar
- **Auto-save**: Auto-save functionality every 30 seconds

##### Rich Text Editor Features
- **Text Formatting**: Bold, italic, underline, strikethrough
- **Headers**: H1, H2, H3, H4, H5, H6
- **Lists**: Ordered lists, unordered lists, nested lists
- **Alignment**: Left, center, right, justify
- **Links**: Internal and external links with validation
- **Images**: Image upload and embedding (future)
- **Tables**: Basic table creation and editing (future)
- **Code Blocks**: Inline code and code blocks (future)
- **Quotes**: Blockquotes and inline quotes
- **Colors**: Text and background colors (limited palette)

##### Rich Text Editor Implementation

```html
<!-- Rich Text Editor Implementation -->
<div x-data="richTextEditor" class="text-editor-container">
    <!-- Editor Toolbar -->
    <div class="editor-toolbar bg-gray-100 dark:bg-gray-700 rounded-t-lg p-3 border border-gray-300 dark:border-gray-600">
        <div class="flex flex-wrap gap-2">
            <!-- Text Formatting -->
            <div class="btn-group">
                <button type="button" @click="formatText('bold')"
                    class="toolbar-btn" title="ตัวหนา (Ctrl+B)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.6 10.79c.97-.67 1.65-1.77 1.65-2.79 0-2.09-2.11-3.83-4.58-3.83H7v12h6.04c2.9 0 4.96-1.83 4.96-4.33 0-1.04-.39-1.85-1.4-2.05z"/>
                        <path d="M7 3h4.83c2.27 0 3.83 1.46 3.83 3.17 0 1.71-1.56 3.17-3.83 3.17H7V3z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('italic')"
                    class="toolbar-btn" title="ตัวเอียง (Ctrl+I)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 4v3h2.21c.45 0 .67.54.67.83 0 .29-.22.83-.67.83H10v5h3.17c.45 0 .67.54.67.83 0 .29-.22.83-.67.83H10v3h4.17c.45 0 .67.54.67.83 0 .29-.22.83-.67.83H10z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('underline')"
                    class="toolbar-btn" title="ขีดเส้นใต้ (Ctrl+U)">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17c3.31 0 6-2.69 6-6V3h-2.5v8c0 1.93-1.57 3.5-3.5 3.5S8.5 12.93 8.5 11V3H6v8c0 3.31 2.69 6 6 6zm-7 2v2h14v-2H5z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('strike')"
                    class="toolbar-btn" title="ขีดขีดทับ">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.56l-1.45-1.45c-.3-.3-.77-.3-1.06 0L12 9.24 9.87 7.11c-.3-.3-.77-.3-1.06 0L7.36 8.56c-.3.3-.3.3-.77 0-1.06L8.43 6.43c.3-.3.77-.3 1.06 0L12 8.94l2.51-2.51c.3-.3.77-.3 1.06 0l1.45 1.45c.3.3.3.77 0 1.06l-1.07 1.07 1.07 1.07c.3.3.3.77 0 1.06z"/>
                    </svg>
                </button>
            </div>

            <!-- Headers -->
            <div class="btn-group">
                <select @change="formatText('header', $event.target.value)"
                    class="toolbar-select" title="หัวข้อ">
                    <option value="">ปกติว</option>
                    <option value="1">หัวข้อ 1</option>
                    <option value="2">หัวข้อ 2</option>
                    <option value="3">หัวข้อ 3</option>
                    <option value="4">หัวข้อ 4</option>
                    <option value="5">หัวข้อ 5</option>
                    <option value="6">หัวข้อ 6</option>
                </select>
            </div>

            <!-- Lists -->
            <div class="btn-group">
                <button type="button" @click="formatText('list', 'ordered')"
                    class="toolbar-btn" title="รายการลำดับ">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 17h2v.5H3v.5h1v.5H2v1h3v-1H4v-.5h1v-.5H4v-.5h1v-1H2zm0-4h2v.5H3v.5h1v.5H2v1h3v-1H4v-.5h1v-.5H4v-.5h1v-1H2zm0-4h2v.5H3v.5h1v.5H2v1h3v-1H4v-.5h1v-.5H4v-.5h1v-1H2zm11-1.5V14l-3.5-2L9 14v-1.5l2.5-1.5L11 7.5v1.5h7V7.5h-7zM2 6h2v.5H3v.5h1v.5H2v1h3V9H4v-.5h1V8H4V7.5h1V7H2v1z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('list', 'bullet')"
                    class="toolbar-btn" title="รายการไม่มีลำดับ">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 10.5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm0-6c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5 1.5-.67 1.5-1.5-.67-1.5-1.5-1.5zm6 6.5h12v-1H10v1zm0-6h12v-1H10v1z"/>
                    </svg>
                </button>
            </div>

            <!-- Alignment -->
            <div class="btn-group">
                <button type="button" @click="formatText('align', '')"
                    class="toolbar-btn" title="จัดชิดซ้าย">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15 15H3v2h12v-2zm0-8H3v2h12V7zM3 13h18v-2H3v2zm0 8h18v-2H3v2zM3 3v2h18V3H3z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('align', 'center')"
                    class="toolbar-btn" title="จัดกลาง">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 15v2h10v-2H7zm-4 6h18v-2H3v2zm0-8h18v-2H3v2zm4-6v2h10V7H7zM3 3v2h18V3H3z"/>
                    </svg>
                </button>
                <button type="button" @click="formatText('align', 'right')"
                    class="toolbar-btn" title="จัดชิดขวา">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 21h18v-2H3v2zm0-4h18v-2H3v2zm0-4h18v-2H3v2zm0-4h18V7H3v2zm0-6v2h18V3H3z"/>
                    </svg>
                </button>
            </div>

            <!-- Links -->
            <div class="btn-group">
                <button type="button" @click="insertLink()"
                    class="toolbar-btn" title="แทรกลิงก์">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5c-.34 0-.66.03-.98.07l-4.73 2.01c-.39.15-.72.48-.87.63l-2.01-4.73z"/>
                    </svg>
                </button>
            </div>

            <!-- Clean -->
            <div class="btn-group">
                <button type="button" @click="formatText('clean')"
                    class="toolbar-btn" title="ล้างการจัดรูปแบบ">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.43 12.98c.04-.32.07-.64.07-.98 0-3.87-3.13-7-7-7s-7 3.13-7 7h.01c0 .34.03.66.07.98l2.01 4.73c.15.37.48.63.87.63l4.73 2.01c.32.04.65.07.98.07 3.87 0 7-3.13 7-7s-3.13-7-7-7c-.34 0-.66.03-.98.07l-4.73 2.01c-.39.15-.72.48-.87.63l-2.01-4.73zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5zm7.43-2.53c.04-.32.07-.64.07-.98 0-3.87-3.13-7-7-7s-7 3.13-7 7h.01c0 .34.03.66.07.98l2.01 4.73c.15.37.48.63.87.63l4.73 2.01c.32.04.65.07.98.07 3.87 0 7-3.13 7-7s-3.13-7-7-7c-.34 0-.66.03-.98.07l-4.73 2.01c-.39.15-.72.48-.87.63l-2.01-4.73z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Editor Content Area -->
    <div id="editor" x-ref="editor"
        class="editor-content min-h-[300px] bg-white dark:bg-gray-800 rounded-b-lg p-4 border border-t-0 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        contenteditable="true"
        @input="updateContent()"
        @keydown="handleKeydown($event)">
    </div>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="content_text" x-model="content" id="content_text">
    
    <!-- Character Count -->
    <div class="flex justify-between items-center mt-2 text-sm text-gray-600 dark:text-gray-400">
        <span>จำนวนตัวอักษร: <span x-text="characterCount">0</span></span>
        <span>จำนวนคำ: <span x-text="wordCount">0</span></span>
    </div>
</div>

<script>
// Rich Text Editor Alpine.js Component
document.addEventListener('alpine:init', () => {
    Alpine.data('richTextEditor', () => ({
        content: '',
        autoSaveInterval: null,
        
        init() {
            // Initialize Quill editor
            this.quill = new Quill(this.$refs.editor, {
                theme: 'snow',
                placeholder: 'เริ่มพิมพ์เนื้อหาที่นี่...',
                modules: {
                    toolbar: false, // We use custom toolbar
                    clipboard: {
                        matchVisual: false
                    }
                },
                formats: [
                    'bold', 'italic', 'underline', 'strike',
                    'header', 'list', 'bullet',
                    'align', 'link', 'clean'
                ]
            });

            // Set initial content if exists
            if (this.content) {
                this.quill.root.innerHTML = this.content;
            }

            // Set up content change listener
            this.quill.on('text-change', () => {
                this.updateContent();
            });

            // Set up auto-save
            this.startAutoSave();

            // Set up keyboard shortcuts
            this.setupKeyboardShortcuts();
        },

        updateContent() {
            this.content = this.quill.root.innerHTML;
            this.updateCounts();
        },

        updateCounts() {
            const text = this.quill.getText();
            this.characterCount = text.length;
            this.wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        },

        formatText(format, value = null) {
            const range = this.quill.getSelection();
            if (range) {
                this.quill.format(format, value, 'user');
            }
        },

        insertLink() {
            const range = this.quill.getSelection();
            if (range) {
                const url = prompt('กรุณาใส่ URL:');
                if (url) {
                    const text = this.quill.getText(range);
                    const link = text || url;
                    this.quill.formatText(range, 'link', url, 'user');
                }
            }
        },

        handleKeydown(event) {
            // Handle common keyboard shortcuts
            if (event.ctrlKey || event.metaKey) {
                switch (event.key) {
                    case 'b':
                        event.preventDefault();
                        this.formatText('bold');
                        break;
                    case 'i':
                        event.preventDefault();
                        this.formatText('italic');
                        break;
                    case 'u':
                        event.preventDefault();
                        this.formatText('underline');
                        break;
                    case 's':
                        event.preventDefault();
                        this.formatText('strike');
                        break;
                }
            }
        },

        setupKeyboardShortcuts() {
            // Additional keyboard shortcuts can be set up here
        },

        startAutoSave() {
            this.autoSaveInterval = setInterval(() => {
                this.autoSave();
            }, 30000); // Auto-save every 30 seconds
        },

        autoSave() {
            if (this.content.trim()) {
                // Send auto-save request to server
                fetch('/lessons/auto-save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content: this.content,
                        lesson_id: '{{ $lesson->id }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Auto-saved at:', new Date());
                    }
                })
                .catch(error => {
                    console.error('Auto-save failed:', error);
                });
            }
        },

        destroy() {
            if (this.autoSaveInterval) {
                clearInterval(this.autoSaveInterval);
            }
        }
    }));
});
</script>

<style>
/* Rich Text Editor Styles */
.text-editor-container {
    @apply w-full;
}

.editor-toolbar {
    @apply flex items-center justify-between;
}

.btn-group {
    @apply flex items-center gap-1;
}

.toolbar-btn {
    @apply p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-600 rounded transition-colors;
}

.toolbar-btn:hover {
    @apply bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300;
}

.toolbar-select {
    @apply px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}

.editor-content {
    font-family: 'Sarabun', 'TH Sarabun New', sans-serif;
    line-height: 1.6;
}

.editor-content h1 { @apply text-2xl font-bold mb-4 mt-6; }
.editor-content h2 { @apply text-xl font-bold mb-3 mt-5; }
.editor-content h3 { @apply text-lg font-bold mb-2 mt-4; }
.editor-content h4 { @apply text-base font-bold mb-2 mt-3; }
.editor-content h5 { @apply text-sm font-bold mb-1 mt-2; }
.editor-content h6 { @apply text-xs font-bold mb-1 mt-2; }

.editor-content ul { @apply list-disc list-inside mb-4; }
.editor-content ol { @apply list-decimal list-inside mb-4; }
.editor-content li { @apply mb-1; }

.editor-content a {
    @apply text-blue-600 dark:text-blue-400 underline hover:text-blue-800 dark:hover:text-blue-300;
}

.editor-content blockquote {
    @apply border-l-4 border-gray-300 dark:border-gray-600 pl-4 italic text-gray-600 dark:text-gray-400 my-4;
}

.editor-content code {
    @apply bg-gray-100 dark:bg-gray-800 px-1 py-0.5 text-sm font-mono text-gray-800 dark:text-gray-200 rounded;
}

.editor-content pre {
    @apply bg-gray-100 dark:bg-gray-800 p-4 rounded-lg overflow-x-auto my-4;
}

.editor-content pre code {
    @apply bg-transparent p-0;
}

/* Quill Editor Customization */
.ql-toolbar {
    @apply border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700;
}

.ql-toolbar .ql-stroke {
    @apply stroke-gray-600 dark:stroke-gray-300;
}

.ql-toolbar .ql-fill {
    @apply fill-gray-600 dark:fill-gray-300;
}

.ql-toolbar .ql-picker {
    @apply text-gray-600 dark:text-gray-300;
}

.ql-container {
    @apply font-sarabun text-gray-900 dark:text-white;
}

.ql-editor {
    @apply min-h-[300px] p-4;
}
</style>
```

##### Rich Text Editor Security Features

```php
// Content Sanitization Service
class RichTextSanitizer
{
    public function sanitize(string $content): string
    {
        // Allowed HTML tags
        $allowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'strike',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'blockquote', 'code', 'pre',
            'a'
        ];

        // Allowed attributes
        $allowedAttributes = [
            'href' => ['a'],
            'title' => ['a'],
            'target' => ['a'],
            'class' => ['pre', 'code']
        ];

        // Sanitize content
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $allowedTags));
        $config->set('HTML.AllowedAttributes', $allowedAttributes);
        $config->set('URI.AllowedSchemes', ['http', 'https', 'mailto']);
        $config->set('AutoFormat.RemoveEmpty', true);
        $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }

    public function validateLink(string $url): bool
    {
        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check for malicious protocols
        $allowedSchemes = ['http', 'https', 'mailto'];
        $scheme = parse_url($url, PHP_URL_SCHEME);
        
        return in_array($scheme, $allowedSchemes);
    }
}
```

##### Rich Text Editor Auto-save Implementation

```php
// Auto-save Controller
class LessonController extends Controller
{
    public function autoSave(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'lesson_id' => 'required|exists:lessons,id'
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);
        
        // Check authorization
        if (!auth()->user()->can('update', $lesson)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Save content to temporary storage or database
        $lesson->temp_content = $request->content;
        $lesson->temp_updated_at = now();
        $lesson->save();

        return response()->json([
            'success' => true,
            'message' => 'Content auto-saved',
            'timestamp' => now()->toISOString()
        ]);
    }
}
```

#### 3. Video Integration
- **YouTube**: Full YouTube API integration
- **Video Info**: Auto-fetch title, duration, thumbnail
- **Embed Options**: Custom player settings
- **Privacy**: Enhanced privacy mode available

```php
// YouTube Video Handler
class YouTubeVideoHandler
{
    public function getVideoInfo(string $url): array
    {
        $videoId = $this->extractVideoId($url);
        
        if (!$videoId) {
            throw new InvalidArgumentException('Invalid YouTube URL');
        }
        
        $apiKey = config('services.youtube.api_key');
        $response = Http::get("https://www.googleapis.com/youtube/v3/videos", [
            'id' => $videoId,
            'key' => $apiKey,
            'part' => 'snippet,contentDetails'
        ]);
        
        $video = $response->json()['items'][0] ?? null;
        
        if (!$video) {
            throw new InvalidArgumentException('Video not found');
        }
        
        return [
            'id' => $videoId,
            'title' => $video['snippet']['title'],
            'description' => $video['snippet']['description'],
            'duration' => $this->parseDuration($video['contentDetails']['duration']),
            'thumbnail' => $video['snippet']['thumbnails']['high']['url'],
            'embed_url' => "https://www.youtube.com/embed/{$videoId}"
        ];
    }
}
```

#### 4. Google Drive Integration
- **Authentication**: OAuth 2.0 with Google
- **File Access**: Public share links
- **Embedding**: Google Docs viewer
- **Supported Types**: Docs, Sheets, Slides, Forms

```php
// Google Drive Handler
class GoogleDriveHandler
{
    public function getEmbedUrl(string $shareUrl): string
    {
        // Extract file ID from share URL
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $shareUrl, $matches)) {
            $fileId = $matches[1];
        } else {
            throw new InvalidArgumentException('Invalid Google Drive URL');
        }
        
        // Generate embed URL
        return "https://drive.google.com/file/d/{$fileId}/preview";
    }
}
```

#### 5. Canva Integration
- **Design Embedding**: Canva design viewer
- **Responsive**: Mobile-friendly embed
- **Privacy**: Public designs only
- **Interactive**: Design interaction support

```php
// Canva Handler
class CanvaHandler
{
    public function getEmbedUrl(string $designUrl): string
    {
        // Extract design ID
        if (preg_match('/\/design\/([a-zA-Z0-9-_]+)/', $designUrl, $matches)) {
            $designId = $matches[1];
        } else {
            throw new InvalidArgumentException('Invalid Canva URL');
        }
        
        // Generate embed URL
        return "https://www.canva.com/design/{$designId}/embed";
    }
}
```

### Technical Details

#### Content Type Validation
```php
class LessonContentValidator
{
    public function validateContent(array $data): array
    {
        $contentType = $data['content_type'];
        $rules = [];
        
        switch ($contentType) {
            case 'PDF':
                $rules = [
                    'content_url' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240'
                ];
                break;
                
            case 'VIDEO':
                $rules = [
                    'content_url' => 'required|url|regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.*/'
                ];
                break;
                
            case 'TEXT':
                $rules = [
                    'content_text' => 'required|string|max:10000'
                ];
                break;
                
            case 'GDRIVE':
                $rules = [
                    'content_url' => 'required|url|regex:/drive\.google\.com/'
                ];
                break;
                
            case 'CANVA':
                $rules = [
                    'content_url' => 'required|url|regex:/canva\.com/'
                ];
                break;
        }
        
        return validator($data, $rules)->validate();
    }
}
```

---

## 📝 Quiz System

### ภาพรวม
ระบบแบบทดสอบแบบ Interactive รองรับ Multiple Choice พร้อม Timer และ Auto-grading

### ฟีเจอร์หลัก

#### 1. Quiz Creation
- **Quiz Settings**: Title, description, time limit, passing score
- **Question Management**: สร้าง/แก้ไข/ลบ คำถาม
- **Answer Options**: 4 choices per question, mark correct answer
- **Question Ordering**: จัดลำดับคำถาม (drag & drop)

```php
// Quiz Management Routes
GET /teacher/modules/{module}/quizzes → QuizController@index
POST /teacher/modules/{module}/quizzes → QuizController@store
PUT /teacher/quizzes/{quiz} → QuizController@update
DELETE /teacher/quizzes/{quiz} → QuizController@destroy
```

#### 2. Question Types
- **Multiple Choice**: 1 correct answer from 4 options
- **True/False**: 2 options (future enhancement)
- **Essay**: Text answer (future enhancement)

#### 3. Quiz Taking Interface
- **Timer Display**: แสดงเวลาค้างเหลือแบบ real-time
- **Question Navigation**: ข้ามคำถามได้
- **Progress Bar**: แสดงความคืบหน้าในการทำแบบทดสอบ
- **Auto-save**: บันทึกคำตอบแบบอัตโนมัติ

```javascript
// Quiz Timer Implementation
class QuizTimer {
    constructor(timeLimit, onTimeUp) {
        this.timeLimit = timeLimit * 60; // Convert to seconds
        this.timeRemaining = this.timeLimit;
        this.onTimeUp = onTimeUp;
        this.interval = null;
    }
    
    start() {
        this.interval = setInterval(() => {
            this.timeRemaining--;
            
            if (this.timeRemaining <= 0) {
                this.stop();
                this.onTimeUp();
            }
            
            this.updateDisplay();
        }, 1000);
    }
    
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }
    
    updateDisplay() {
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        
        document.getElementById('timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}
```

#### 4. Auto-grading System
- **Instant Results**: ตรวจคำตอบทันที
- **Score Calculation**: คำนวณคะแนนอัตโนมัติ
- **Pass/Fail**: กำหนดผ่าน/ไม่ผ่านตาม passing score
- **Detailed Feedback**: แสดงคำตอบที่ถูกต้อง

```php
// Quiz Grading Service
class QuizGradingService
{
    public function gradeQuiz(Quiz $quiz, array $answers): array
    {
        $questions = $quiz->questions()->with('answers')->get();
        $totalQuestions = $questions->count();
        $correctAnswers = 0;
        $results = [];
        
        foreach ($questions as $question) {
            $userAnswerId = $answers[$question->id] ?? null;
            $correctAnswerId = $question->answers
                ->where('is_correct', true)
                ->first()
                ->id;
            
            $isCorrect = $userAnswerId === $correctAnswerId;
            
            if ($isCorrect) {
                $correctAnswers++;
            }
            
            $results[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'user_answer' => $userAnswerId,
                'correct_answer' => $correctAnswerId,
                'is_correct' => $isCorrect
            ];
        }
        
        $score = ($correctAnswers / $totalQuestions) * 100;
        $passed = $score >= $quiz->passing_score;
        
        return [
            'score' => round($score, 2),
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'passed' => $passed,
            'results' => $results
        ];
    }
}
```

#### 5. Quiz Attempts & Retakes
- **Attempt Tracking**: บันทึกครั้งที่ทำแบบทดสอบ
- **Attempt Limit**: จำกัดจำนวนครั้ง (configurable)
- **Best Score**: เก็บคะแนนสูงสุด
- **Attempt History**: ดูประวัติการทำแบบทดสอบ

### Technical Details

#### Quiz Model Structure
```php
class Quiz extends Model
{
    protected $fillable = [
        'module_id', 'title', 'description', 'passing_score', 'time_limit'
    ];

    protected $casts = [
        'passing_score' => 'decimal:2',
        'time_limit' => 'integer'
    ];

    // Relationships
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Methods
    public function getBestScoreForStudent(int $studentId): ?float
    {
        return $this->attempts()
            ->where('student_id', $studentId)
            ->max('score');
    }

    public function getAttemptsCountForStudent(int $studentId): int
    {
        return $this->attempts()
            ->where('student_id', $studentId)
            ->count();
    }
}
```

#### Question Model Structure
```php
class Question extends Model
{
    protected $fillable = [
        'quiz_id', 'question_text', 'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('order');
    }

    public function correctAnswer()
    {
        return $this->hasOne(Answer::class)->where('is_correct', true);
    }

    // Methods
    public function shuffleAnswers(): Collection
    {
        return $this->answers->shuffle();
    }
}
```

#### Quiz Attempt Model Structure
```php
class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'score', 'passed', 
        'started_at', 'completed_at', 'time_taken'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_taken' => 'integer'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Computed Properties
    public function getTimeTakenFormattedAttribute(): string
    {
        $minutes = floor($this->time_taken / 60);
        $seconds = $this->time_taken % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
```

---

## 📊 Progress Tracking

### ภาพรวม
ระบบติดตามความคืบหน้าการเรียนแบบ Real-time พร้อมการแสดงผลเป็นภาพและสถิติ

### ฟีเจอร์หลัก

#### 1. Lesson Completion Tracking
- **Real-time Updates**: อัพเดทความคืบหน้าทันที
- **AJAX Completion**: ไม่ต้อง reload หน้า
- **Completion History**: บันทึกประวัติการเรียนเสร็จ
- **Progress Calculation**: คำนวณ % ความคืบหน้าอัตโนมัติ

```javascript
// Lesson Completion Handler
class LessonCompletionHandler {
    static async completeLesson(lessonId) {
        try {
            const response = await fetch(`/lessons/${lessonId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.updateUI(data);
                this.showSuccessNotification();
            }
        } catch (error) {
            console.error('Error completing lesson:', error);
            this.showErrorNotification();
        }
    }
    
    static updateUI(data) {
        // Update completion button
        const button = document.getElementById(`complete-btn-${lessonId}`);
        if (button) {
            button.textContent = '✅ Completed';
            button.disabled = true;
            button.classList.add('bg-green-500', 'text-white');
        }
        
        // Update progress bar
        const progressBar = document.getElementById('course-progress');
        if (progressBar) {
            progressBar.style.width = `${data.progress}%`;
            progressBar.textContent = `${data.progress}%`;
        }
        
        // Update lesson list
        const lessonItem = document.getElementById(`lesson-${lessonId}`);
        if (lessonItem) {
            lessonItem.classList.add('completed');
            const badge = lessonItem.querySelector('.completion-badge');
            if (badge) {
                badge.textContent = '✅';
                badge.classList.remove('hidden');
            }
        }
    }
}
```

#### 2. Course Progress Calculation
- **Module Progress**: ความคืบหน้าในแต่ละโมดูล
- **Course Progress**: ความคืบหน้ารวมของคอร์ส
- **Overall Progress**: ความคืบหน้าเฉลี่ยของคอร์สทั้งหมด
- **Progress Visualization**: Progress bars, charts, badges

```php
// Progress Calculation Service
class ProgressCalculationService
{
    public function calculateCourseProgress(Course $course, int $studentId): array
    {
        $totalLessons = $course->modules->sum(function ($module) {
            return $module->lessons->count();
        });
        
        $completedLessons = $course->modules->sum(function ($module) use ($studentId) {
            return $module->lessons()
                ->whereHas('completions', function ($query) use ($studentId) {
                    $query->where('user_id', $studentId);
                })
                ->count();
        });
        
        $progressPercentage = $totalLessons > 0 
            ? round(($completedLessons / $totalLessons) * 100, 2)
            : 0;
        
        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
            'is_completed' => $progressPercentage >= 100,
            'modules_progress' => $this->calculateModulesProgress($course, $studentId)
        ];
    }
    
    public function calculateModulesProgress(Course $course, int $studentId): array
    {
        return $course->modules->map(function ($module) use ($studentId) {
            $totalLessons = $module->lessons->count();
            $completedLessons = $module->lessons()
                ->whereHas('completions', function ($query) use ($studentId) {
                    $query->where('user_id', $studentId);
                })
                ->count();
            
            $progressPercentage = $totalLessons > 0
                ? round(($completedLessons / $totalLessons) * 100, 2)
                : 0;
            
            return [
                'module_id' => $module->id,
                'module_title' => $module->title,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percentage' => $progressPercentage,
                'is_completed' => $progressPercentage >= 100
            ];
        })->toArray();
    }
}
```

#### 3. Student Dashboard
- **Course Cards**: แสดงคอร์สที่ลงทะเบียนพร้อม progress
- **Progress Overview**: สถิติการเรียนโดยรวม
- **Recent Activity**: บทเรียน/แบบทดสอบล่าสุด
- **Achievements**: Badges และความสำเร็จ

#### 4. Teacher Analytics
- **Class Statistics**: จำนวนนักเรียน, completion rate
- **Individual Progress**: ความคืบหน้าของแต่ละคน
- **Popular Content**: บทเรียนที่นิยมมากที่สุด
- **Performance Metrics**: Quiz scores, time spent

### Technical Details

#### Progress Tracking Database Schema
```sql
-- Lesson Completions Table
CREATE TABLE lesson_completions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (lesson_id, user_id),
    INDEX idx_user_lesson (user_id, lesson_id)
);

-- Course Progress View (Optional)
CREATE VIEW course_progress_view AS
SELECT 
    c.id as course_id,
    c.title as course_title,
    u.id as user_id,
    u.name as user_name,
    COUNT(l.id) as total_lessons,
    COUNT(lc.id) as completed_lessons,
    ROUND((COUNT(lc.id) / COUNT(l.id)) * 100, 2) as progress_percentage,
    CASE 
        WHEN COUNT(lc.id) = COUNT(l.id) THEN 1 
        ELSE 0 
    END as is_completed
FROM courses c
JOIN users u ON 1=1
JOIN modules m ON m.course_id = c.id
JOIN lessons l ON l.module_id = m.id
LEFT JOIN lesson_completions lc ON lc.lesson_id = l.id AND lc.user_id = u.id
LEFT JOIN enrollments e ON e.course_id = c.id AND e.user_id = u.id
WHERE e.id IS NOT NULL
GROUP BY c.id, u.id;
```

#### Real-time Progress Updates
```php
// Lesson Completion Controller
class LessonCompletionController extends Controller
{
    public function complete(Lesson $lesson)
    {
        $user = auth()->user();
        
        // Check if already completed
        if ($lesson->isCompletedByStudent($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson already completed'
            ]);
        }
        
        // Mark as complete
        LessonCompletion::create([
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
            'completed_at' => now()
        ]);
        
        // Calculate new progress
        $course = $lesson->module->course;
        $progress = $course->getProgressForStudent($user->id);
        
        // Check for course completion
        if ($progress['progress_percentage'] >= 100) {
            event(new CourseCompleted($user, $course));
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as complete',
            'data' => [
                'lesson_id' => $lesson->id,
                'course_progress' => $progress['progress_percentage'],
                'module_progress' => $progress['modules_progress'][$lesson->module->id]['progress_percentage'] ?? 0
            ]
        ]);
    }
}
```

---

## 🎓 Certificate System

### ภาพรวม
ระบบออกใบประกาศนียบัตร PDF อัตโนมัติเมื่อนักเรียนเรียนครบคอร์สและผ่านแบบทดสอบ

### ฟีเจอร์หลัก

#### 1. Automatic Certificate Generation
- **Completion Check**: ตรวจสอบเงื่อนไขการเรียนจบ
- **PDF Generation**: สร้าง PDF จาก template
- **Unique Numbers**: สร้างเลขที่อ้างอิงไม่ซ้ำกัน
- **Digital Signature**: รองรับลายเซ็นดิจิทัล

#### 2. Certificate Template
- **Professional Design**: รูปแบบเอกสารสวยงาม
- **Dynamic Content**: แทรกข้อมูลนักเรียน/คอร์สอัตโนมัติ
- **Customizable**: สามารถแก้ไข template ได้
- **Multiple Languages**: รองรับหลายภาษา

#### 3. Certificate Management
- **Download**: ดาวน์โหลด PDF
- **View Online**: ดูใบประกาศนียบัตรออนไลน์
- **Share**: แชร์ลิงก์ใบประกาศนียบัตร
- **Verification**: ตรวจสอบความถูกต้องของใบประกาศนียบัตร

#### 4. Certificate Requirements
- **Course Completion**: เรียนครบทุกบทเรียน
- **Quiz Passing**: ผ่านแบบทดสอบทุกโมดูล
- **Minimum Score**: คะแนนรวมตามเกณฑ์
- **No Duplicate**: ยังไม่เคยได้รับใบประกาศนียบัตรนี้

### Technical Details

#### Certificate Model Structure
```php
class Certificate extends Model
{
    protected $fillable = [
        'student_id', 'course_id', 'certificate_number', 
        'issued_at', 'pdf_path'
    ];

    protected $casts = [
        'issued_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Static Methods
    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        $sequence = Certificate::whereYear('issued_at', $year)->count() + 1;
        
        return sprintf('CT-%s-%06d', $year, $sequence);
    }

    public static function canIssueCertificate(User $student, Course $course): bool
    {
        // Check if already has certificate
        $existingCertificate = Certificate::where([
            'student_id' => $student->id,
            'course_id' => $course->id
        ])->first();
        
        if ($existingCertificate) {
            return false;
        }
        
        // Check course completion
        $progress = $course->getProgressForStudent($student->id);
        if ($progress['progress_percentage'] < 100) {
            return false;
        }
        
        // Check quiz completion
        $quizzes = $course->modules->pluck('quiz')->filter();
        foreach ($quizzes as $quiz) {
            $bestScore = $quiz->getBestScoreForStudent($student->id);
            if (!$bestScore || $bestScore < $quiz->passing_score) {
                return false;
            }
        }
        
        return true;
    }
}
```

#### PDF Generation Service
```php
class CertificateService
{
    public function generateCertificate(User $student, Course $course): Certificate
    {
        // Create certificate record
        $certificate = Certificate::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issued_at' => now()
        ]);
        
        // Generate PDF
        $pdf = $this->generatePDF($certificate, $student, $course);
        
        // Save PDF
        $filename = "certificate_{$certificate->id}.pdf";
        $path = "certificates/{$filename}";
        Storage::disk('public')->put($path, $pdf->output());
        
        // Update certificate with PDF path
        $certificate->update(['pdf_path' => $path]);
        
        return $certificate;
    }
    
    private function generatePDF(Certificate $certificate, User $student, Course $course)
    {
        $pdf = PDF::loadView('certificates.template', [
            'certificate' => $certificate,
            'student' => $student,
            'course' => $course,
            'teacher' => $course->teacher
        ]);
        
        // Set PDF options
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sarabun'
        ]);
        
        return $pdf;
    }
}
```

#### Certificate Template (Blade)
```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ใบประกาศนียบัตร</title>
    <style>
        @font-face {
            font-family: 'Sarabun';
            src: url('{{ public_path('fonts/sarabun.ttf') }}') format('truetype');
        }
        
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .certificate {
            background: white;
            border: 15px solid #d4af37;
            padding: 60px;
            text-align: center;
            max-width: 1000px;
            margin: 0 auto;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid #d4af37;
        }
        
        .header {
            margin-bottom: 40px;
        }
        
        .title {
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 24px;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-style: italic;
        }
        
        .logo {
            width: 120px;
            margin-bottom: 30px;
        }
        
        .recipient {
            font-size: 32px;
            color: #34495e;
            margin: 40px 0;
            font-weight: bold;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .course-info {
            font-size: 20px;
            color: #2c3e50;
            margin: 30px 0;
            line-height: 1.6;
        }
        
        .course-title {
            font-size: 24px;
            font-weight: bold;
            color: #d4af37;
            margin: 20px 0;
        }
        
        .completion-text {
            font-size: 18px;
            color: #555;
            margin: 20px 0;
            font-style: italic;
        }
        
        .certificate-number {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 50px;
            border: 1px solid #ddd;
            display: inline-block;
            padding: 10px 20px;
            background: #f9f9f9;
        }
        
        .date {
            font-size: 16px;
            color: #7f8c8d;
            margin-top: 20px;
        }
        
        .signatures {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        
        .signature-block {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-bottom: 2px solid #333;
            width: 200px;
            margin: 10px auto;
            height: 60px;
        }
        
        .signature-title {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 10px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(212, 175, 55, 0.1);
            font-weight: bold;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="watermark">CT LEARNING</div>
        
        <div class="header">
            <div class="title">ใบประกาศนียบัตร</div>
            <div class="subtitle">Certificate of Completion</div>
        </div>
        
        <div class="recipient">
            มอบให้แก่ {{ $student->name }}
        </div>
        
        <div class="course-info">
            ได้เข้าร่วมอบรมคอร์สเรียน<br>
            <div class="course-title">{{ $course->title }}</div>
            <div class="completion-text">
                และผ่านการประเมินผลการเรียนเรียบร้อย
            </div>
        </div>
        
        <div class="certificate-number">
            เลขที่: {{ $certificate->certificate_number }}
        </div>
        
        <div class="date">
            ออกให้วันที่: {{ $certificate->issued_at->format('d F Y') }}
        </div>
        
        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line">
                    @if($course->teacher->signature)
                        <img src="{{ Storage::url($course->teacher->signature) }}" style="max-height: 60px;">
                    @endif
                </div>
                <div class="signature-title">ครูผู้สอน</div>
                <div>{{ $course->teacher->name }}</div>
            </div>
            
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">ผู้อำนวยการ</div>
                <div>ผู้อำนวยการแผนกเทคโนโลยีคอมพิวเตอร์</div>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 📁 File Upload System

### ภาพรวม
ระบบจัดการไฟล์อัพโหลดที่ปลอดภัยและมีประสิทธิภาพสูง รองรับหลายประเภทไฟล์

### ฟีเจอร์หลัก

#### 1. Multi-type File Support
- **Images**: JPG, PNG, GIF, WebP
- **Documents**: PDF, DOC, DOCX, PPT, PPTX
- **Videos**: MP4, AVI, MOV (future)
- **Audio**: MP3, WAV (future)

#### 2. File Validation & Security
- **Type Validation**: ตรวจสอบ MIME type
- **Size Limits**: จำกัดขนาดไฟล์ตามประเภท
- **Virus Scanning**: สแกนไวรัส (future enhancement)
- **File Sanitization**: ล้างข้อมูลที่เป็นอันตราย

#### 3. Image Processing
- **Resizing**: สร้างรูปหลายขนาดอัตโนมัติ
- **Optimization**: บีบอัดขนาดไฟล์
- **Watermarking**: เพิ่มลายน้ำ (optional)
- **Format Conversion**: แปลงรูปแบบอัตโนมัติ

#### 4. Storage Management
- **Local Storage**: จัดเก็บใน server
- **Cloud Storage**: รองรับ AWS S3, Google Cloud (future)
- **Symbolic Links**: การเชื่อมโยงไฟล์สู่ public
- **File Organization**: จัดเก็บตามประเภทและวันที่

### Technical Details

#### File Upload Handler
```php
class FileUploadService
{
    public function handleUpload(UploadedFile $file, string $type, array $options = []): array
    {
        // Validate file
        $this->validateFile($file, $type);
        
        // Generate unique filename
        $filename = $this->generateFilename($file);
        
        // Process file if needed
        $processedFile = $this->processFile($file, $type, $options);
        
        // Store file
        $path = $this->storeFile($processedFile, $type, $filename);
        
        // Create file record
        $fileRecord = FileRecord::create([
            'original_name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'url' => Storage::url($path),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'type' => $type,
            'uploaded_by' => auth()->id()
        ]);
        
        return [
            'id' => $fileRecord->id,
            'original_name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'url' => Storage::url($path),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }
    
    private function validateFile(UploadedFile $file, string $type): void
    {
        $rules = $this->getFileValidationRules($type);
        
        $validator = validator(['file' => $file], ['file' => $rules]);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
    
    private function getFileValidationRules(string $type): array
    {
        $rules = [
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'document' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'video' => 'file|mimes:mp4,avi,mov|max:51200',
            'audio' => 'file|mimes:mp3,wav|max:10240'
        ];
        
        return $rules[$type] ?? $rules['image'];
    }
    
    private function processFile(UploadedFile $file, string $type, array $options): UploadedFile
    {
        if ($type === 'image') {
            return $this->processImage($file, $options);
        }
        
        return $file;
    }
    
    private function processImage(UploadedFile $file, array $options): UploadedFile
    {
        $image = Image::make($file);
        
        // Resize if needed
        if (isset($options['max_width']) || isset($options['max_height'])) {
            $image->resize(
                $options['max_width'] ?? null,
                $options['max_height'] ?? null,
                function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
        }
        
        // Add watermark if needed
        if (isset($options['watermark']) && $options['watermark']) {
            $this->addWatermark($image);
        }
        
        // Optimize
        $image->encode('jpg', 85);
        
        // Save to temporary file
        $tempPath = tempnam(sys_get_temp_dir(), 'processed_image_');
        $image->save($tempPath);
        
        return new UploadedFile($tempPath, $file->getClientOriginalName());
    }
    
    private function generateFilename(UploadedFile $file): string
    {
        return time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
    }
    
    private function storeFile(UploadedFile $file, string $type, string $filename): string
    {
        $directory = "uploads/{$type}/" . date('Y/m/d');
        
        return $file->storeAs($directory, $filename, 'public');
    }
}
```

#### File Model Structure
```php
class FileRecord extends Model
{
    protected $fillable = [
        'original_name', 'filename', 'path', 'url', 
        'size', 'mime_type', 'type', 'uploaded_by'
    ];

    protected $casts = [
        'size' => 'integer'
    ];

    // Relationships
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function getIsDocumentAttribute(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ]);
    }
}
```

---

## 🔔 Notification System

### ภาพรวม
ระบบแจ้งเตือนแบบ Multi-channel สำหรับการสื่อสารกับผู้ใช้

### ฟีเจอร์หลัก

#### 1. Notification Types
- **System Notifications**: ข้อความจากระบบ
- **Course Updates**: การอัพเดทคอร์ส/บทเรียน
- **Quiz Reminders**: แจ้งเตือนแบบทดสอบ
- **Achievement Unlocks**: ปลดล็อกความสำเร็จ
- **Certificate Issued**: ออกใบประกาศนียบัตร

#### 2. Delivery Channels
- **In-App**: แสดงในระบบ
- **Email**: ส่งทางอีเมล
- **SMS**: ส่ง SMS (future)
- **Push Notifications**: Browser push notifications (future)

#### 3. Notification Management
- **Read/Unread**: ติดตามสถานะการอ่าน
- **Preferences**: ตั้งค่าการรับแจ้งเตือน
- **History**: ประวัติการแจ้งเตือน
- **Bulk Actions**: จัดการแจ้งเตือนหลายรายการ

### Technical Details

#### Notification Model Structure
```php
class Notification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 
        'data', 'read_at', 'sent_at'
    ];

    protected $casts = [
        'data' => 'json',
        'read_at' => 'datetime',
        'sent_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    // Methods
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
```

#### Notification Service
```php
class NotificationService
{
    public function sendNotification(User $user, string $type, string $title, string $message, array $data = []): Notification
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'sent_at' => now()
        ]);

        // Send via channels based on user preferences
        $this->sendViaChannels($user, $notification);

        return $notification;
    }

    private function sendViaChannels(User $user, Notification $notification): void
    {
        $preferences = $user->notification_preferences ?? [];

        // In-App Notification (always sent)
        $this->sendInAppNotification($user, $notification);

        // Email Notification
        if ($preferences['email'] ?? true) {
            $this->sendEmailNotification($user, $notification);
        }

        // SMS Notification (future)
        if ($preferences['sms'] ?? false) {
            $this->sendSMSNotification($user, $notification);
        }

        // Push Notification (future)
        if ($preferences['push'] ?? false) {
            $this->sendPushNotification($user, $notification);
        }
    }

    private function sendInAppNotification(User $user, Notification $notification): void
    {
        // Notification is already stored in database
        // Real-time update can be sent via WebSocket or Server-Sent Events
        broadcast(new NotificationSent($notification))->toOthers();
    }

    private function sendEmailNotification(User $user, Notification $notification): void
    {
        Mail::to($user->email)->send(new NotificationEmail($notification));
    }
}
```

---

## 🎛️ Admin Panel

### ภาพรวม
แผงควบคุมระบบสำหรับผู้ดูแลระบบ มีเครื่องมือครบถ้วนสำหรับการจัดการระบบ

### ฟีเจอร์หลัก

#### 1. Dashboard Overview
- **System Statistics**: จำนวนผู้ใช้, คอร์ส, การลงทะเบียน
- **Growth Charts**: กราฟการเติบโตของระบบ
- **Recent Activity**: กิจกรรมล่าสุดในระบบ
- **Quick Actions**: ปุ่มดำเนินงานด่วน

#### 2. User Management
- **User List**: ดูรายชื่อผู้ใช้ทั้งหมด
- **User Details**: ดูข้อมูลผู้ใช้แบบละเอียด
- **Role Management**: เปลี่ยน role ผู้ใช้
- **Bulk Operations**: ลบ/อัพเดท/ส่งอีเมลหลายรายการ
- **User Statistics**: สถิติการใช้งานของผู้ใช้

#### 3. Course Management
- **Course List**: ดูคอร์สทั้งหมดในระบบ
- **Course Statistics**: สถิติคอร์ส (นักเรียน, completion rate)
- **Course Approval**: อนุมัติคอร์ส (ถ้ามีการกำหนด)
- **Course Categories**: จัดการหมวดหมู่คอร์ส

#### 4. System Configuration
- **General Settings**: ชื่อระบบ, อีเมล, timezone
- **Feature Toggles**: เปิด/ปิดฟีเจอร์ต่างๆ
- **Email Configuration**: ตั้งค่า SMTP
- **Security Settings**: ค่าความปลอดภัย
- **Backup Settings**: ค่าการสำรองข้อมูล

#### 5. Reporting & Analytics
- **Usage Reports**: รายงานการใช้งานระบบ
- **Performance Reports**: รายงานประสิทธิภาพ
- **Financial Reports**: รายงานการเงิน (ถ้ามี)
- **Export Data**: ส่งออกข้อมูลเป็น CSV/Excel

### Technical Details

#### Admin Controller Structure
```php
class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_certificates' => Certificate::count(),
            'users_by_role' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role'),
            'recent_enrollments' => Enrollment::with(['user', 'course'])
                ->orderBy('enrolled_at', 'desc')
                ->limit(10)
                ->get(),
            'monthly_growth' => $this->getMonthlyGrowthData()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $users = User::withCount(['coursesTaught', 'enrollments'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function courses(Request $request)
    {
        $courses = Course::with(['teacher', 'enrollments'])
            ->withCount('enrollments')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.courses.index', compact('courses'));
    }

    private function getMonthlyGrowthData(): array
    {
        $months = collect(range(11, 0))->map(function ($month) {
            return now()->subMonths($month)->format('Y-m');
        });

        $userGrowth = $months->map(function ($month) {
            return User::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
        });

        $courseGrowth = $months->map(function ($month) {
            return Course::whereYear('created_at', substr($month, 0, 4))
                ->whereMonth('created_at', substr($month, 5, 2))
                ->count();
        });

        return [
            'months' => $months->map(function ($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M Y');
            }),
            'users' => $userGrowth,
            'courses' => $courseGrowth
        ];
    }
}
```

---

## 📈 Reporting & Analytics

### ภาพรวม
ระบบรายงานและวิเคราะห์ข้อมูลสำหรับการตัดสินใจและการบริหารระบบ

### ฟีเจอร์หลัก

#### 1. User Analytics
- **Registration Trends**: แนวโน้การสมัครสมาชิก
- **User Demographics**: ข้อมูลประชากรผู้ใช้
- **Activity Patterns**: รูปแบบการใช้งาน
- **Retention Rates**: อัตราการกลับมาใช้

#### 2. Course Analytics
- **Enrollment Statistics**: สถิติการลงทะเบียน
- **Completion Rates**: อัตราการเรียนจบ
- **Popular Courses**: คอร์สยอดนิยม
- **Revenue Reports**: รายงานรายได้ (ถ้ามี)

#### 3. Learning Analytics
- **Progress Patterns**: รูปแบบความคืบหน้า
- **Time Spent**: เวลาที่ใช้ในการเรียน
- **Quiz Performance**: ผลการทำแบบทดสอบ
- **Learning Paths**: พฤติกรรมการเรียน

#### 4. System Analytics
- **Performance Metrics**: ประสิทธิภาพระบบ
- **Error Reports**: รายงานข้อผิดพลาด
- **Usage Statistics**: สถิติการใช้งาน
- **Resource Utilization**: การใช้ทรัพยากร

### Technical Details

#### Analytics Service
```php
class AnalyticsService
{
    public function getUserAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->orderBy('date')
                ->get(),
            'users_by_role' => User::selectRaw('role, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('role')
                ->get(),
            'active_users' => User::whereHas('enrollments', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('enrolled_at', [$startDate, $endDate]);
                })->count()
        ];
    }

    public function getCourseAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_courses' => Course::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_enrollments' => Enrollment::whereBetween('enrolled_at', [$startDate, $endDate])->count(),
            'enrollment_trends' => Enrollment::whereBetween('enrolled_at', [$startDate, $endDate])
                ->groupBy('date')
                ->selectRaw('DATE(enrolled_at) as date, COUNT(*) as count')
                ->orderBy('date')
                ->get(),
            'popular_courses' => Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->limit(10)
                ->get(),
            'completion_rates' => $this->getCourseCompletionRates($startDate, $endDate)
        ];
    }

    public function getLearningAnalytics(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'total_lesson_completions' => LessonCompletion::whereBetween('completed_at', [$startDate, $endDate])->count(),
            'average_completion_time' => $this->getAverageCompletionTime($startDate, $endDate),
            'quiz_performance' => $this->getQuizPerformance($startDate, $endDate),
            'learning_paths' => $this->getLearningPaths($startDate, $endDate)
        ];
    }

    private function getCourseCompletionRates(Carbon $startDate, Carbon $endDate): Collection
    {
        return Course::with(['enrollments' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('enrolled_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($course) {
                $totalEnrollments = $course->enrollments->count();
                $completedEnrollments = $course->enrollments
                    ->filter(function ($enrollment) {
                        return $enrollment->user->hasCompletedCourse($course->id);
                    })
                    ->count();

                $completionRate = $totalEnrollments > 0 
                    ? ($completedEnrollments / $totalEnrollments) * 100 
                    : 0;

                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'total_enrollments' => $totalEnrollments,
                    'completed_enrollments' => $completedEnrollments,
                    'completion_rate' => round($completionRate, 2)
                ];
            });
    }
}
```

---

## 📚 Summary

CT Learning มีฟีเจอร์ครบถ้วนที่ครอบคลุมทุกด้านของระบบ Learning Management System:

### ✅ Core Features
- **Multi-role Authentication** - ระบบยืนยันตัวตนแบบหลายบทบาท
- **Course Management** - จัดการคอร์สเรียนแบบครบถ้วน
- **Content Management** - รองรับหลายรูปแบบเนื้อหา
- **Quiz System** - ระบบแบบทดสอบพร้อม auto-grading
- **Progress Tracking** - ติดตามความคืบหน้าแบบ real-time
- **Certificate System** - ออกใบประกาศนียบัตรอัตโนมัติ
- **File Upload** - ระบบจัดการไฟล์ที่ปลอดภัย
- **Admin Panel** - แผงควบคุมระบบสำหรับผู้ดูแล
- **Analytics** - ระบบรายงานและวิเคราะห์ข้อมูล

### 🎯 จุดเด่น
- **User-Friendly Interface**: ออกแบบตามหลักการณ์สมัยใหม่
- **Mobile Responsive**: รองรับทุกขนาดหน้าจอ
- **Real-time Updates**: อัพเดทข้อมูลแบบทันที
- **Security First**: ความปลอดภัยเป็นสำคัญ
- **Scalable Architecture**: โครงสร้างรองรับการขยาย
- **Performance Optimized**: ทำงานได้อย่างรวดเร็ว

### 🚀 พร้อมพัฒนาต่อ
- **Modular Design**: โครงสร้างแบบโมดูลที่แยกชัดเจน
- **Well Documented**: เอกสารครบถ้วน
- **Clean Code**: โค้ดที่เป็นระเบียบและอ่านง่าย
- **Testable**: โครงสร้างที่รองรับการทดสอบ
- **Extensible**: ง่ายต่อการเพิ่มฟีเจอร์ใหม่

---

**อัพเดทล่าสุด**: 29 พฤศจิกายน 2025  
**เวอร์ชัน**: v2.0  
**ผู้เขียน**: CT Learning Team  
**สถานะ**: ✅ Complete & Enhanced  

---

<p align="center">
  <strong>🎯 CT Learning - Feature Documentation</strong><br>
  <em>Complete guide to all features and functionality</em>
</p>