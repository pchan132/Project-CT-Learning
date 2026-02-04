<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    /**
     * Display the signature management page.
     */
    public function index()
    {
        $teacher = auth()->user();
        return view('teacher.signature.index', compact('teacher'));
    }

    /**
     * Upload or update teacher signature.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'signature_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'signature_image.required' => 'กรุณาเลือกรูปภาพลายเซ็น',
            'signature_image.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'signature_image.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg, gif',
            'signature_image.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ]);

        $teacher = auth()->user();

        // Delete old signature if exists
        if ($teacher->signature_image) {
            Storage::disk('public')->delete($teacher->signature_image);
        }

        // Store new signature
        $path = $request->file('signature_image')->store('signatures', 'public');
        
        $teacher->update([
            'signature_image' => $path
        ]);

        return redirect()->route('teacher.signature.index')
            ->with('success', 'อัปโหลดลายเซ็นสำเร็จ!');
    }

    /**
     * Delete teacher signature.
     */
    public function delete()
    {
        $teacher = auth()->user();

        if ($teacher->signature_image) {
            Storage::disk('public')->delete($teacher->signature_image);
            
            $teacher->update([
                'signature_image' => null
            ]);

            return redirect()->route('teacher.signature.index')
                ->with('success', 'ลบลายเซ็นสำเร็จ!');
        }

        return redirect()->route('teacher.signature.index')
            ->with('error', 'ไม่พบลายเซ็นที่ต้องการลบ');
    }

    /**
     * Preview certificate with signature.
     */
    public function preview()
    {
        $teacher = auth()->user();
        return view('teacher.signature.preview', compact('teacher'));
    }

    /**
     * Upload or update teacher certificate background.
     */
    public function uploadBackground(Request $request)
    {
        $request->validate([
            'certificate_background' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'certificate_background.required' => 'กรุณาเลือกรูปภาพพื้นหลัง',
            'certificate_background.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'certificate_background.mimes' => 'รูปภาพต้องเป็นไฟล์ประเภท: jpeg, png, jpg',
            'certificate_background.max' => 'ขนาดรูปภาพต้องไม่เกิน 5MB',
        ]);

        $teacher = auth()->user();

        // Delete old background if exists
        if ($teacher->certificate_background) {
            Storage::disk('public')->delete($teacher->certificate_background);
        }

        // Store new background
        $path = $request->file('certificate_background')->store('certificate-backgrounds/teachers', 'public');
        
        $teacher->update([
            'certificate_background' => $path
        ]);

        return redirect()->route('teacher.signature.index')
            ->with('success', 'อัปโหลดภาพพื้นหลังใบประกาศนียบัตรสำเร็จ!');
    }

    /**
     * Delete teacher certificate background.
     */
    public function deleteBackground()
    {
        $teacher = auth()->user();

        if ($teacher->certificate_background) {
            Storage::disk('public')->delete($teacher->certificate_background);
            
            $teacher->update([
                'certificate_background' => null
            ]);

            return redirect()->route('teacher.signature.index')
                ->with('success', 'ลบภาพพื้นหลังสำเร็จ! (จะใช้พื้นหลังเริ่มต้นของระบบแทน)');
        }

        return redirect()->route('teacher.signature.index')
            ->with('error', 'ไม่พบภาพพื้นหลังที่ต้องการลบ');
    }
}
