# Google Drive Lesson Support

## ภาพรวม
ระบบรองรับการใส่ลิงก์ Google Drive ในบทเรียน ทำให้ครูสามารถแชร์เนื้อหาจาก Google Drive เช่น เอกสาร, สไลด์, สเปรดชีต, รูปภาพ และวิดีโอได้โดยตรง

## วิธีการใช้งาน (สำหรับครู)

### การสร้างบทเรียน Google Drive
1. ไปที่หน้าสร้างบทเรียนใหม่
2. เลือกประเภทเนื้อหา **"Google Drive"**
3. วางลิงก์ Google Drive ที่ช่อง URL
4. กดบันทึก

### การเตรียมลิงก์ Google Drive
1. เปิดไฟล์ใน Google Drive ของคุณ
2. คลิกขวาที่ไฟล์ → **แชร์**
3. เปลี่ยนการเข้าถึงเป็น **"ทุกคนที่มีลิงก์"**
4. คัดลอกลิงก์และวางในระบบ

### รูปแบบ URL ที่รองรับ

#### ไฟล์ทั่วไป (PDF, รูปภาพ, วิดีโอ)
```
https://drive.google.com/file/d/FILE_ID/view?usp=sharing
https://drive.google.com/file/d/FILE_ID/view
```

#### Google Docs (เอกสาร)
```
https://docs.google.com/document/d/DOCUMENT_ID/edit
```

#### Google Slides (สไลด์)
```
https://docs.google.com/presentation/d/PRESENTATION_ID/edit
```

#### Google Sheets (สเปรดชีต)
```
https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/edit
```

## การแปลง URL สำหรับ Embed

ระบบจะแปลง URL โดยอัตโนมัติเป็นรูปแบบ embed:

| ประเภท | URL เดิม | URL Embed |
|--------|----------|-----------|
| ไฟล์ทั่วไป | `/file/d/ID/view` | `/file/d/ID/preview` |
| Docs | `/document/d/ID/edit` | `/document/d/ID/preview` |
| Slides | `/presentation/d/ID/edit` | `/presentation/d/ID/preview` |
| Sheets | `/spreadsheets/d/ID/edit` | `/spreadsheets/d/ID/preview` |

## ไฟล์ที่แก้ไข

### Model
- `app/Models/Lesson.php`
  - เพิ่ม `getGoogleDriveEmbedUrl()` method
  - เพิ่ม `isGoogleDriveContent()` method
  - อัปเดต `getContentDisplayUrlAttribute()` เพื่อรองรับ GDRIVE
  - อัปเดต `getContentTypeLabelAttribute()` เพื่อแสดง "Google Drive"

### Controller
- `app/Http/Controllers/Teacher/LessonController.php`
  - เพิ่ม validation สำหรับ `content_type: GDRIVE`
  - เพิ่ม validation สำหรับ `gdrive_url`
  - จัดการ Google Drive URL ใน store และ update methods

### Views

#### Teacher Views
- `resources/views/teacher/lessons/create.blade.php`
  - เพิ่ม radio button สำหรับ GDRIVE
  - เพิ่ม gdrive-field div พร้อม URL input
  - อัปเดต JavaScript toggleFields() รองรับ GDRIVE

- `resources/views/teacher/lessons/edit.blade.php`
  - เพิ่ม radio button สำหรับ GDRIVE
  - เพิ่ม gdrive-field div พร้อม URL input
  - อัปเดต JavaScript toggleFields() รองรับ GDRIVE

- `resources/views/teacher/lessons/index.blade.php`
  - เพิ่ม GDRIVE badge style
  - เพิ่ม Google Drive icon

- `resources/views/teacher/lessons/show.blade.php`
  - เพิ่ม Google Drive content display
  - เพิ่ม iframe embed สำหรับ preview

#### Student Views
- `resources/views/student/lessons/learn.blade.php`
  - เพิ่ม GDRIVE icon ใน lesson meta
  - เพิ่ม case GDRIVE สำหรับแสดงเนื้อหา
  - เพิ่ม iframe embed และปุ่มเปิดใน Google Drive

## Content Types ที่รองรับ

| Type | Description | Icon |
|------|-------------|------|
| PDF | PDF Document | fa-file-pdf (red) |
| VIDEO | วิดีโอ YouTube/อัปโหลด | fa-video (purple) |
| TEXT | ข้อความ Rich Text | fa-align-left (gray) |
| **GDRIVE** | **Google Drive** | **fab fa-google-drive (yellow)** |
| PPT | PowerPoint | fa-file-powerpoint (orange) |

## การทดสอบ

### Test Case 1: สร้างบทเรียน Google Drive
1. Login เป็น Teacher
2. ไปที่คอร์ส → Module → สร้างบทเรียน
3. เลือก "Google Drive"
4. ใส่ลิงก์: `https://drive.google.com/file/d/1ABC123/view?usp=sharing`
5. กดบันทึก
6. ตรวจสอบว่าบทเรียนถูกสร้าง

### Test Case 2: Student ดูบทเรียน Google Drive
1. Login เป็น Student
2. ลงทะเบียนคอร์สที่มีบทเรียน Google Drive
3. เปิดบทเรียน
4. ตรวจสอบว่า iframe แสดงเนื้อหาจาก Google Drive

### Test Case 3: แก้ไขบทเรียน Google Drive
1. Login เป็น Teacher
2. แก้ไขบทเรียน Google Drive
3. เปลี่ยน URL
4. กดบันทึก
5. ตรวจสอบว่า URL ถูกอัปเดต

## ข้อจำกัด

1. **การแชร์**: ไฟล์ต้องถูกแชร์เป็น "ทุกคนที่มีลิงก์" จึงจะแสดงได้
2. **ความเป็นส่วนตัว**: ไฟล์ที่ต้องการความปลอดภัยสูงไม่ควรใช้วิธีนี้
3. **การดาวน์โหลด**: นักเรียนอาจดาวน์โหลดไฟล์ได้ขึ้นอยู่กับการตั้งค่าการแชร์

## สถานะ
✅ **เสร็จสมบูรณ์** - 25 มกราคม 2025
