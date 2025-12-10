<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, Module $module)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สและ module เป็นของคอร์สนี้
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $lessons = $module->lessons()->ordered()->get();

        return view('teacher.lessons.index', compact('course', 'module', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course, Module $module)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สและ module เป็นของคอร์สนี้
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        // หาลำดับถัดไปสำหรับ lesson ใหม่
        $nextOrder = $module->lessons()->max('order') + 1;

        return view('teacher.lessons.create', compact('course', 'module', 'nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, Module $module)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สและ module เป็นของคอร์สนี้
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:PDF,VIDEO,TEXT,GDRIVE,CANVA',
            'content_url' => 'nullable|string|max:500',
            'content_text' => 'nullable|required_if:content_type,TEXT|string',
            'file' => 'nullable|required_if:content_type,PDF,PPT|file|mimes:pdf,ppt,pptx|max:10240', // 10MB max
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:102400', // 100MB max
            'video_type' => 'nullable|in:url,upload',
            'gdrive_url' => 'nullable|required_if:content_type,GDRIVE|url|max:500',
            'canva_url' => 'nullable|required_if:content_type,CANVA|url|max:500',
            'order' => 'required|integer|min:1',
            'required_duration_minutes' => 'nullable|integer|min:1|max:1440', // 1 minute to 24 hours
        ]);

        // Set default required_duration_minutes if not provided
        if (!isset($data['required_duration_minutes']) || empty($data['required_duration_minutes'])) {
            $data['required_duration_minutes'] = 1;
        }

        // จัดการ file upload สำหรับ PDF/PPT
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $contentType = $request->input('content_type');
            
            $folder = $contentType === 'PDF' ? 'lessons/pdf' : 'lessons/ppt';
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $path = $file->storeAs($folder, $filename, 'public');
            $data['content_url'] = $path;
        }

        // จัดการ video upload
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $filename = time() . '_' . $videoFile->getClientOriginalName();
            
            $path = $videoFile->storeAs('lessons/videos', $filename, 'public');
            $data['content_url'] = $path;
        }

        // จัดการ Google Drive URL
        if ($request->input('content_type') === 'GDRIVE' && $request->input('gdrive_url')) {
            $data['content_url'] = $request->input('gdrive_url');
        }

        // จัดการ Canva URL
        if ($request->input('content_type') === 'CANVA' && $request->input('canva_url')) {
            $data['content_url'] = $request->input('canva_url');
        }

        // ตรวจสอบว่า order ซ้ำหรือไม่
        $existingLesson = $module->lessons()->where('order', $data['order'])->first();
        if ($existingLesson) {
            // ถ้าซ้ำ ให้ shift lessons ที่มี order >= ที่กรอก
            $module->lessons()->where('order', '>=', $data['order'])->increment('order');
        }

        $data['module_id'] = $module->id;

        Lesson::create($data);

        return redirect()
            ->route('teacher.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson ถูกสร้างเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Module $module, Lesson $lesson)
    {
        // ตรวจสอบสิทธิ์
        if ($course->teacher_id !== auth()->id() || 
            $module->course_id !== $course->id || 
            $lesson->module_id !== $module->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('teacher.lessons.show', compact('course', 'module', 'lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Module $module, Lesson $lesson)
    {
        // ตรวจสอบสิทธิ์
        if ($course->teacher_id !== auth()->id() || 
            $module->course_id !== $course->id || 
            $lesson->module_id !== $module->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('teacher.lessons.edit', compact('course', 'module', 'lesson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Module $module, Lesson $lesson)
    {
        // ตรวจสอบสิทธิ์
        if ($course->teacher_id !== auth()->id() || 
            $module->course_id !== $course->id || 
            $lesson->module_id !== $module->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content_type' => 'required|in:PDF,VIDEO,TEXT,GDRIVE,CANVA',
            'content_url' => 'nullable|string|max:500',
            'content_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240', // 10MB max
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:102400', // 100MB max
            'video_type' => 'nullable|in:url,upload',
            'gdrive_url' => 'nullable|required_if:content_type,GDRIVE|url|max:500',
            'canva_url' => 'nullable|required_if:content_type,CANVA|url|max:500',
            'order' => 'required|integer|min:1',
            'required_duration_minutes' => 'nullable|integer|min:1|max:1440', // 1 minute to 24 hours
        ]);

        // Set default required_duration_minutes if not provided
        if (!isset($data['required_duration_minutes']) || empty($data['required_duration_minutes'])) {
            $data['required_duration_minutes'] = 1;
        }

        // จัดการ file upload ถ้ามีการอัปโหลดไฟล์ใหม่
        if ($request->hasFile('file')) {
            // ลบไฟล์เก่าถ้ามี
            if ($lesson->content_url && $lesson->isFileContent()) {
                Storage::disk('public')->delete($lesson->content_url);
            }

            $file = $request->file('file');
            $contentType = $request->input('content_type');
            
            $folder = $contentType === 'PDF' ? 'lessons/pdf' : 'lessons/ppt';
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $path = $file->storeAs($folder, $filename, 'public');
            $data['content_url'] = $path;
        }

        // จัดการ video upload
        if ($request->hasFile('video_file')) {
            // ลบไฟล์เก่าถ้ามี
            if ($lesson->content_url && $lesson->isVideoContent() && !filter_var($lesson->content_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($lesson->content_url);
            }

            $videoFile = $request->file('video_file');
            $filename = time() . '_' . $videoFile->getClientOriginalName();
            
            $path = $videoFile->storeAs('lessons/videos', $filename, 'public');
            $data['content_url'] = $path;
        }

        // จัดการ Google Drive URL
        if ($request->input('content_type') === 'GDRIVE' && $request->input('gdrive_url')) {
            $data['content_url'] = $request->input('gdrive_url');
        }

        // จัดการ Canva URL
        if ($request->input('content_type') === 'CANVA' && $request->input('canva_url')) {
            $data['content_url'] = $request->input('canva_url');
        }
        
        // ถ้าเปลี่ยน content type แต่ไม่อัปโหลดไฟล์ใหม่
        if (!$request->hasFile('file') && !$request->hasFile('video_file') && $request->input('content_type') !== $lesson->content_type) {
            // ลบ URL เก่าถ้าเป็นไฟล์
            if ($lesson->content_url && ($lesson->isFileContent() || ($lesson->isVideoContent() && !filter_var($lesson->content_url, FILTER_VALIDATE_URL)))) {
                Storage::disk('public')->delete($lesson->content_url);
            }
            $data['content_url'] = null;
        }

        // ถ้าเปลี่ยน order
        if ($data['order'] != $lesson->order) {
            $existingLesson = $module->lessons()
                ->where('order', $data['order'])
                ->where('id', '!=', $lesson->id)
                ->first();

            if ($existingLesson) {
                // ถ้ามี lesson อื่นอยู่ใน order นั้น ให้ shift
                if ($data['order'] > $lesson->order) {
                    // ย้ายไปข้างหลัง - shift lessons ระหว่าง old+1 ถึง new ลง
                    $module->lessons()
                        ->where('order', '>', $lesson->order)
                        ->where('order', '<=', $data['order'])
                        ->decrement('order');
                } else {
                    // ย้ายไปข้างหน้า - shift lessons ระหว่าง new ถึง old-1 ขึ้น
                    $module->lessons()
                        ->where('order', '>=', $data['order'])
                        ->where('order', '<', $lesson->order)
                        ->increment('order');
                }
            }
        }

        $lesson->update($data);

        return redirect()
            ->route('teacher.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson ถูกอัปเดตเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Module $module, Lesson $lesson)
    {
        // ตรวจสอบสิทธิ์
        if ($course->teacher_id !== auth()->id() || 
            $module->course_id !== $course->id || 
            $lesson->module_id !== $module->id) {
            abort(403, 'Unauthorized action.');
        }

        // ลบไฟล์ถ้ามี
        if ($lesson->content_url && $lesson->isFileContent()) {
            Storage::disk('public')->delete($lesson->content_url);
        }

        // เก็บ order ปัจจุบันไว้
        $deletedOrder = $lesson->order;

        $lesson->delete();

        // Shift lessons ที่มี order > ที่ลบ ลงมา 1 ตำแหน่ง
        $module->lessons()
            ->where('order', '>', $deletedOrder)
            ->decrement('order');

        return redirect()
            ->route('teacher.courses.modules.lessons.index', [$course, $module])
            ->with('success', 'Lesson ถูกลบเรียบร้อยแล้ว');
    }

    /**
     * Reorder lessons via drag-and-drop
     */
    public function reorder(Request $request, Course $course, Module $module)
    {
        // ตรวจสอบว่าครูเป็นเจ้าของคอร์สและ module เป็นของคอร์สนี้
        if ($course->teacher_id !== auth()->id() || $module->course_id !== $course->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $order = $request->input('order', []);

        foreach ($order as $index => $lessonId) {
            Lesson::where('id', $lessonId)
                ->where('module_id', $module->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Upload image for Quill Editor
     */
    public static function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
        ], [
            'image.required' => 'กรุณาเลือกรูปภาพ',
            'image.image' => 'ไฟล์ที่เลือกต้องเป็นรูปภาพ',
            'image.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif, webp',
            'image.max' => 'รูปภาพต้องมีขนาดไม่เกิน 2MB',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            $path = $image->storeAs('lessons/images', $filename, 'public');
            
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ไม่พบรูปภาพ'
        ], 400);
    }
}