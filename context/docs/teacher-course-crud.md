# เอกสารการทำ CRUD คอร์สเรียนสำหรับครูผู้สอน (Teacher Course CRUD)

## ภาพรวมระบบ

เอกสารนี้อธิบายการทำงานของระบบจัดการคอร์สเรียนสำหรับครูผู้สอน ซึ่งประกอบด้วยการสร้าง (Create), อ่าน (Read), อัพเดท (Update), และลบ (Delete) คอร์สเรียน

## โครงสร้างฐานข้อมูล

### ตาราง courses

```sql
- id (Primary Key)
- teacher_id (Foreign Key ไปยัง users)
- title (string) - ชื่อคอร์ส
- description (text) - รายละเอียดคอร์ส
- cover_image_url (string) - ที่อยู่รูปภาพหน้าปก
- created_at, updated_at (timestamps)
```

## โครงสร้างไฟล์

```
app/
├── Models/
│   └── Course.php                    # Model สำหรับจัดการข้อมูลคอร์ส
├── Http/Controllers/Teacher/
│   └── CourseController.php          # Controller สำหรับ Teacher
resources/views/teacher/courses/
├── index.blade.php                   # หน้าแสดงรายการคอร์ส
├── create.blade.php                  # ฟอร์มสร้างคอร์สใหม่
└── edit.blade.php                    # ฟอร์มแก้ไขคอร์ส
routes/
└── web.php                           # การกำหนดเส้นทาง
```

## การกำหนดเส้นทาง (Routes)

### Resource Route สำหรับ Teacher

```php
// ใน routes/web.php
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
});
```

### เส้นทางที่สร้างโดยอัตโนมัติ

| Method | URI | Action | Route Name |
|--------|-----|--------|------------|
| GET | `/teacher/courses` | index | teacher.courses.index |
| GET | `/teacher/courses/create` | create | teacher.courses.create |
| POST | `/teacher/courses` | store | teacher.courses.store |
| GET | `/teacher/courses/{course}` | show | teacher.courses.show |
| GET | `/teacher/courses/{course}/edit` | edit | teacher.courses.edit |
| PUT/PATCH | `/teacher/courses/{course}` | update | teacher.courses.update |
| DELETE | `/teacher/courses/{course}` | destroy | teacher.courses.destroy |

## Model: Course

### คุณสมบัติและความสัมพันธ์

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        "teacher_id",
        "title", 
        "description",
        "cover_image_url"
    ];

    // ความสัมพันธ์: คอร์สเป็นของครูผู้สอน 1 คน
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // ความสัมพันธ์: คอร์สมีการลงทะเบียนเรียนได้หลายรายการ
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
```

## Controller: Teacher/CourseController

### 1. Index - แสดงรายการคอร์สทั้งหมด

```php
public function index()
{
    // ดึงเฉพาะคอร์สที่ครูคนนี้เป็นเจ้าของ
    $courses = Course::where('teacher_id', auth()->id())->get();
    return view('teacher.courses.index', compact('courses'));
}
```

**การทำงาน:**
- ค้นหาคอร์สทั้งหมดที่มี `teacher_id` ตรงกับครูที่ล็อกอินอยู่
- ส่งข้อมูลไปแสดงในหน้า `teacher.courses.index`

### 2. Create - แสดงฟอร์มสร้างคอร์สใหม่

```php
public function create()
{
    return view('teacher.courses.create');
}
```

**การทำงาน:**
- แสดงฟอร์มสำหรับสร้างคอร์สใหม่
- ไม่ต้องส่งข้อมูลใดๆ เนื่องจากเป็นฟอร์มว่าง

### 3. Store - บันทึกคอร์สใหม่

```php
public function store(Request $request)
{
    // ตรวจสอบข้อมูลที่ส่งมา
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'cover_image_url' => 'image|mimes:jpeg,png,jpg,gif,svg',
    ]);

    // เพิ่ม ID ของครูที่ล็อกอิน
    $data['teacher_id'] = auth()->id();

    // จัดการอัพโหลดรูปภาพ (ถ้ามี)
    if ($request->hasFile('cover_image_url')) {
        $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
    }

    // สร้างคอร์สใหม่
    Course::create($data);
    
    // กลับไปหน้ารายการคอร์ส
    return redirect()->route('teacher.courses.index');
}
```

**การทำงาน:**
- ตรวจสอบความถูกต้องของข้อมูล (Validation)
- อัพโหลดรูปภาพไปยัง `storage/app/public/cover_images`
- สร้างข้อมูลคอร์สใหม่ในฐานข้อมูล
- รีไดเร็กกลับหน้ารายการคอร์ส

### 4. Show - แสดงรายละเอียดคอร์ส

```php
public function show(Course $course)
{
    // ตรวจสอบว่าเป็นเจ้าของคอร์สหรือไม่
    if ($course->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    return view('teacher.courses.show', compact('course'));
}
```

**การทำงาน:**
- ตรวจสอบสิทธิ์ว่าครูเป็นเจ้าของคอร์สนี้จริง
- แสดงรายละเอียดคอร์สในหน้า `teacher.courses.show`

### 5. Edit - แสดงฟอร์มแก้ไขคอร์ส

```php
public function edit(Course $course)
{
    if ($course->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    return view('teacher.courses.edit', compact('course'));
}
```

**การทำงาน:**
- ตรวจสอบสิทธิ์เจ้าของคอร์ส
- แสดงฟอร์มแก้ไขพร้อมข้อมูลเดิมของคอร์ส

### 6. Update - อัพเดทข้อมูลคอร์ส

```php
public function update(Request $request, Course $course)
{
    // ตรวจสอบสิทธิ์
    if ($course->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // ตรวจสอบข้อมูล (cover_image_url เป็น optional)
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
    ]);

    // จัดการรูปภาพใหม่ (ถ้ามีการอัพโหลด)
    if ($request->hasFile('cover_image_url')) {
        $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
    }

    // อัพเดทข้อมูลคอร์ส
    $course->update($data);
    
    return redirect()->route('teacher.courses.index');
}
```

**การทำงาน:**
- ตรวจสอบสิทธิ์และความถูกต้องของข้อมูล
- อัพโหลดรูปภาพใหม่ (ถ้ามี)
- อัพเดทข้อมูลในฐานข้อมูล
- รีไดเร็กกลับหน้ารายการคอร์ส

### 7. Destroy - ลบคอร์ส

```php
public function destroy(Course $course)
{
    // ตรวจสอบสิทธิ์
    if ($course->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // ลบรูปภาพหน้าปก (ถ้ามี)
    if ($course->cover_image_url) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_image_url);
    }
    
    // ลบข้อมูลคอร์ส
    $course->delete();
    
    return redirect()->route('teacher.courses.index');
}
```

**การทำงาน:**
- ตรวจสอบสิทธิ์เจ้าของคอร์ส
- ลบรูปภาพจาก storage (ถ้ามี)
- ลบข้อมูลคอร์สจากฐานข้อมูล
- รีไดเร็กกลับหน้ารายการคอร์ส

## Views (Blade Templates)

### 1. Index View - หน้าแสดงรายการคอร์ส

**ไฟล์:** `resources/views/teacher/courses/index.blade.php`

**ฟีเจอร์หลัก:**
- แสดงรายการคอร์สทั้งหมดในรูปแบบตาราง
- แสดงรูปภาพหน้าปก (ถ้ามี)
- ปุ่มสร้างคอร์สใหม่
- ปุ่มแก้ไขและลบสำหรับแต่ละคอร์ส
- การยืนยันก่อนลบคอร์ส

**โค้ดสำคัญ:**
```php
@foreach ($courses as $course)
    <tr class="border-b hover:bg-gray-50">
        <td class="py-3">
            @if ($course->cover_image_url)
                <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                     class="w-20 h-12 object-cover rounded" alt="{{ $course->title }}">
            @else
                <div class="w-20 h-12 bg-gray-200 rounded"></div>
            @endif
        </td>
        <td class="py-3 font-medium">{{ $course->title }}</td>
        <td class="py-3 text-gray-600">{{ Str::limit($course->description, 50) }}</td>
        <td class="py-3">
            <a href="{{ route('teacher.courses.edit', $course->id) }}" class="text-blue-600 hover:underline">
                Edit
            </a>
            <form action="{{ route('teacher.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">Delete</button>
            </form>
        </td>
    </tr>
@endforeach
```

### 2. Create View - ฟอร์มสร้างคอร์สใหม่

**ไฟล์:** `resources/views/teacher/courses/create.blade.php`

**ฟีเจอร์หลัก:**
- ฟอร์มสำหรับป้อนข้อมูลคอร์สใหม่
- ช่องกรอกชื่อคอร์ส (จำเป็น)
- ช่องกรอกรายละเอียด (จำเป็น)
- ช่องอัพโหลดรูปภาพหน้าปก (ไม่จำเป็น)
- ปุ่มยกเลิกและสร้างคอร์ส

**โค้ดสำคัญ:**
```php
<form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div>
        <label class="block font-medium">Title</label>
        <input type="text" name="title" class="w-full border rounded-md p-2 mt-1" required>
    </div>

    <div>
        <label class="block font-medium">Description</label>
        <textarea name="description" class="w-full border rounded-md p-2 mt-1" rows="4"></textarea>
    </div>

    <div>
        <label class="block font-medium">Cover Image</label>
        <input type="file" name="cover_image_url" accept="image/*" class="mt-2">
    </div>
</form>
```

### 3. Edit View - ฟอร์มแก้ไขคอร์ส

**ไฟล์:** `resources/views/teacher/courses/edit.blade.php`

**ฟีเจอร์หลัก:**
- ฟอร์มสำหรับแก้ไขข้อมูลคอร์ส
- แสดงข้อมูลเดิมในฟอร์ม
- แสดงรูปภาพหน้าปกปัจจุบัน
- ช่องสำหรับอัพโหลดรูปภาพใหม่ (ไม่จำเป็น)

**โค้ดสำคัญ:**
```php
<form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-medium">Title</label>
        <input type="text" name="title" value="{{ $course->title }}" class="w-full border rounded-md p-2 mt-1" required>
    </div>

    <div>
        <label class="block font-medium">Current Cover Image</label>
        @if ($course->cover_image_url)
            <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="Cover Image" class="h-32 w-32 object-cover rounded mt-2">
        @else
            <p class="text-gray-500 mt-2">No cover image uploaded</p>
        @endif
    </div>

    <div>
        <label class="block font-medium">Upload New Cover Image</label>
        <input type="file" name="cover_image_url" accept="image/*" class="mt-2">
        <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
    </div>
</form>
```

## การจัดการรูปภาพ

### การอัพโหลดรูปภาพ
- ใช้ `enctype="multipart/form-data"` ในฟอร์ม
- รูปภาพจะถูกเก็บใน `storage/app/public/cover_images`
- ใช้ `store()` method สำหรับการอัพโหลด

### การแสดงรูปภาพ
- ใช้ `asset('storage/' . $course->cover_image_url)` สำหรับแสดงรูป
- ต้องรันคำสั่ง `php artisan storage:link` ก่อน

### การลบรูปภาพ
- ใช้ `Storage::disk('public')->delete()` สำหรับลบรูป
- ลบรูปก่อนลบข้อมูลคอร์ส

## การตรวจสอบสิทธิ์ (Authorization)

ทุก method ใน Controller มีการตรวจสอบว่าครูเป็นเจ้าของคอร์ส:

```php
if ($course->teacher_id !== auth()->id()) {
    abort(403, 'Unauthorized action.');
}
```

## การตรวจสอบข้อมูล (Validation)

### การสร้างคอร์ส (Store)
```php
$data = $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'cover_image_url' => 'image|mimes:jpeg,png,jpg,gif,svg',
]);
```

### การแก้ไขคอร์ส (Update)
```php
$data = $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
]);
```

## การติดตั้งและการตั้งค่า

### 1. สร้าง Symbolic Link
```bash
php artisan storage:link
```

### 2. ตรวจสอบการตั้งค่าใน `config/filesystems.php`
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

## ตัวอย่างการใช้งาน

### 1. การสร้างคอร์สใหม่
1. ไปที่ `/teacher/courses/create`
2. กรอกข้อมูลคอร์ส (ชื่อ, รายละเอียด)
3. เลือกรูปภาพหน้าปก (ไม่จำเป็น)
4. กดปุ่ม "Create"
5. ระบบจะบันทึกข้อมูลและกลับไปหน้ารายการคอร์ส

### 2. การแก้ไขคอร์ส
1. ไปที่หน้ารายการคอร์ส `/teacher/courses`
2. กดปุ่ม "Edit" ที่คอร์สที่ต้องการแก้ไข
3. แก้ไขข้อมูลในฟอร์ม
4. อัพโหลดรูปภาพใหม่ (ถ้าต้องการ)
5. กดปุ่ม "Update"

### 3. การลบคอร์ส
1. ไปที่หน้ารายการคอร์ส
2. กดปุ่ม "Delete" ที่คอร์สที่ต้องการลบ
3. ยืนยันการลบใน dialog box
4. ระบบจะลบคอร์สและรูปภาพที่เกี่ยวข้อง

## ข้อควรพิจารณาเพิ่มเติม

### ความปลอดภัย
- มีการตรวจสอบสิทธิ์ในทุกการดำเนินการ
- ตรวจสอบประเภทไฟล์รูปภาพ
- จำกัดขนาดไฟล์ (สามารถเพิ่มได้ใน validation)

### ประสิทธิภาพ
- ใช้ Eloquent ORM สำหรับการจัดการฐานข้อมูล
- ใช้ Lazy Loading สำหรับความสัมพันธ์
- ควรพิจารณาใช้ Pagination สำหรับข้อมูลจำนวนมาก

### การพัฒนาต่อ
- เพิ่มฟีเจอร์ค้นหาและกรองคอร์ส
- เพิ่มการจัดการรูปภาพขั้นสูง (resize, compression)
- เพิ่มการบันทึก activity log
- เพิ่มฟีเจอร์ duplicate course
- เพิ่มการจัดการสถานะคอร์ส (draft, published, archived)

## สรุป

ระบบ CRUD สำหรับคอร์สเรียนของครูผู้สอนนี้มีโครงสร้างที่ชัดเจน ปลอดภัย และใช้งานง่าย สามารถนำไปพัฒนาต่อเพื่อเพิ่มฟีเจอร์เพิ่มเติมได้ตามความต้องการของโปรเจกต์