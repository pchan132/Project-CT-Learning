# การแก้ไขปัญหาการอัพโหลดรูปภาพและการลบคอร์ส

## ปัญหาที่พบ

1. **การอัพโหลดรูปภาพไม่ขึ้น** - ชื่อฟิลด์ไม่ตรงกันระหว่างฟอร์มและ Controller
2. **การแสดงรูปภาพในหน้า index ผิดพลาด** - path ไม่ถูกต้อง
3. **การลบคอร์สไม่ได้** - ไม่มี Policy และ Route ซ้ำซ้อน

## วิธีการแก้ไข

### 1. แก้ไขชื่อฟิลด์รูปภาพในฟอร์ม

**ไฟล์:** `resources/views/teacher/courses/create.blade.php`

```php
// ก่อนแก้ไข
<input type="file" name="cover" accept="image/*" class="mt-2">

// หลังแก้ไข
<input type="file" name="cover_image_url" accept="image/*" class="mt-2">
```

### 2. เพิ่มการจัดการรูปภาพใน Controller

**ไฟล์:** `app/Http/Controllers/Teacher/CourseController.php`

#### ใน method store()
```php
// เพิ่มรูปภาพหน้าปก
if ($request->hasFile('cover_image_url')) {
    $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
}
```

#### ใน method update()
```php
// ทำให้รูปภาพเป็น optional
'cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',

// จัดการรูปภาพหน้าปกใหม่ (ถ้ามี)
if ($request->hasFile('cover_image_url')) {
    $data['cover_image_url'] = $request->file('cover_image_url')->store('cover_images', 'public');
}
```

### 3. แก้ไขการแสดงรูปภาพในหน้า index

**ไฟล์:** `resources/views/teacher/courses/index.blade.php`

```php
// ก่อนแก้ไข
<img src="{{ ./storage/cover_images/ . $course->cover_image_url }}"

// หลังแก้ไข
<img src="{{ asset('storage/' . $course->cover_image_url) }}"
```

### 4. สร้างฟอร์มแก้ไขคอร์ส

**ไฟล์ใหม่:** `resources/views/teacher/courses/edit.blade.php`

```php
<x-app-layout>
    <div class="max-w-xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Edit Course</h1>
        
        <form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- ฟิลด์ title, description -->
            
            <div>
                <label class="block font-medium">Current Cover Image</label>
                @if($course->cover_image_url)
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
    </div>
</x-app-layout>
```

### 5. แก้ไขปัญหาการลบคอร์ส

#### แก้ไข Controller - เปลี่ยนจาก Policy เป็นตรวจสอบ teacher_id

**ไฟล์:** `app/Http/Controllers/Teacher/CourseController.php`

```php
// ในทุก method (show, edit, update, destroy)
if ($course->teacher_id !== auth()->id()) {
    abort(403, 'Unauthorized action.');
}

// ใน destroy method - ลบไฟล์ก่อนลบ record
public function destroy(Course $course)
{
    if ($course->teacher_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    // ลบรูปภาพหน้าปกถ้ามี (ก่อนลบ record)
    if ($course->cover_image_url) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($course->cover_image_url);
    }
    
    $course->delete();
    return redirect()->route('teacher.courses.index');
}
```

#### แก้ไข Route - ลบ Route ซ้ำซ้อน

**ไฟล์:** `routes/web.php`

```php
// ใช้แค่ resource route อันเดียว
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('courses', TeacherCourseController::class);
});

// ลบส่วนนี้ออก (ซ้ำซ้อน)
// Route::get('/teacher/courses', [TeacherCourseController::class, 'index'])
// Route::delete('/teacher/courses/{course}', [TeacherCourseController::class, 'destroy'])
```

### 6. ตรวจสอบ Storage Link

รันคำสั่งเพื่อสร้าง symbolic link สำหรับ storage:

```bash
php artisan storage:link
```

## การตรวจสอบว่าใช้งานได้

1. **อัพโหลดรูปภาพ**: ลองสร้างคอร์สใหม่และอัพโหลดรูปภาพ
2. **แสดงรูปภาพ**: ตรวจสอบว่ารูปภาพขึ้นในหน้ารายการคอร์ส
3. **แก้ไขคอร์ส**: ลองแก้ไขคอร์สและอัพโหลดรูปภาพใหม่
4. **ลบคอร์ส**: ลองลบคอร์สและตรวจสอบว่ารูปภาพถูกลบด้วย

## โครงสร้างไฟล์ที่เกี่ยวข้อง

```
app/Http/Controllers/Teacher/CourseController.php
resources/views/teacher/courses/create.blade.php
resources/views/teacher/courses/edit.blade.php
resources/views/teacher/courses/index.blade.php
routes/web.php
storage/app/public/cover_images/ (โฟลเดอร์เก็บรูปภาพ)
public/storage (symbolic link)
```

## ข้อควรระวัง

- ตรวจสอบให้แน่ใจว่ามี `enctype="multipart/form-data"` ในฟอร์มอัพโหลดไฟล์
- ใช้ `asset()` helper สำหรับการแสดงรูปภาพจาก storage
- ลบไฟล์ก่อนลบ record ในฐานข้อมูลเพื่อป้องกันไฟล์ทิ้งไว้
- ตรวจสอบสิทธิ์ผู้ใช้ก่อนทำการ CRUD ทุกครั้ง