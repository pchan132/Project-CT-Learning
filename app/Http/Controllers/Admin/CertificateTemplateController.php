<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    /**
     * Display a listing of certificate templates.
     */
    public function index()
    {
        $templates = CertificateTemplate::with('creator')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.certificate-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return view('admin.certificate-templates.create');
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'admin_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'border_color' => 'required|string|max:7',
            'primary_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'admin_name' => 'nullable|string|max:255',
            'admin_position' => 'nullable|string|max:255',
            'show_teacher_signature' => 'boolean',
            'teacher_signature_position' => 'in:left,right',
            'admin_signature_position' => 'in:left,right',
        ]);

        // Handle file uploads
        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')
                ->store('certificate-templates/backgrounds', 'public');
        }

        if ($request->hasFile('logo_image')) {
            $validated['logo_image'] = $request->file('logo_image')
                ->store('certificate-templates/logos', 'public');
        }

        if ($request->hasFile('admin_signature')) {
            $validated['admin_signature'] = $request->file('admin_signature')
                ->store('certificate-templates/signatures', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['show_teacher_signature'] = $request->boolean('show_teacher_signature');

        $template = CertificateTemplate::create($validated);

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('success', 'สร้าง Template ใหม่สำเร็จ!');
    }

    /**
     * Display the specified template.
     */
    public function show(CertificateTemplate $certificateTemplate)
    {
        return view('admin.certificate-templates.show', [
            'template' => $certificateTemplate
        ]);
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(CertificateTemplate $certificateTemplate)
    {
        return view('admin.certificate-templates.edit', [
            'template' => $certificateTemplate
        ]);
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'admin_signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'border_color' => 'required|string|max:7',
            'primary_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'admin_name' => 'nullable|string|max:255',
            'admin_position' => 'nullable|string|max:255',
            'show_teacher_signature' => 'boolean',
            'teacher_signature_position' => 'in:left,right',
            'admin_signature_position' => 'in:left,right',
        ]);

        // Handle file uploads
        if ($request->hasFile('background_image')) {
            // Delete old file
            if ($certificateTemplate->background_image) {
                Storage::disk('public')->delete($certificateTemplate->background_image);
            }
            $validated['background_image'] = $request->file('background_image')
                ->store('certificate-templates/backgrounds', 'public');
        }

        if ($request->hasFile('logo_image')) {
            if ($certificateTemplate->logo_image) {
                Storage::disk('public')->delete($certificateTemplate->logo_image);
            }
            $validated['logo_image'] = $request->file('logo_image')
                ->store('certificate-templates/logos', 'public');
        }

        if ($request->hasFile('admin_signature')) {
            if ($certificateTemplate->admin_signature) {
                Storage::disk('public')->delete($certificateTemplate->admin_signature);
            }
            $validated['admin_signature'] = $request->file('admin_signature')
                ->store('certificate-templates/signatures', 'public');
        }

        $validated['show_teacher_signature'] = $request->boolean('show_teacher_signature');

        $certificateTemplate->update($validated);

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('success', 'อัพเดท Template สำเร็จ!');
    }

    /**
     * Remove the specified template.
     */
    public function destroy(CertificateTemplate $certificateTemplate)
    {
        // Don't allow deleting if there are certificates using this template
        if ($certificateTemplate->certificates()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีใบประกาศนียบัตรที่ใช้ Template นี้อยู่');
        }

        // Delete associated files
        if ($certificateTemplate->background_image) {
            Storage::disk('public')->delete($certificateTemplate->background_image);
        }
        if ($certificateTemplate->logo_image) {
            Storage::disk('public')->delete($certificateTemplate->logo_image);
        }
        if ($certificateTemplate->admin_signature) {
            Storage::disk('public')->delete($certificateTemplate->admin_signature);
        }

        $certificateTemplate->delete();

        return redirect()
            ->route('admin.certificate-templates.index')
            ->with('success', 'ลบ Template สำเร็จ!');
    }

    /**
     * Set template as active
     */
    public function setActive(CertificateTemplate $certificateTemplate)
    {
        $certificateTemplate->setAsActive();

        return back()->with('success', 'ตั้งเป็น Template หลักสำเร็จ!');
    }

    /**
     * Preview template with sample data
     */
    public function preview(CertificateTemplate $certificateTemplate)
    {
        // ดึงคอร์สทั้งหมดสำหรับ dropdown
        $courses = \App\Models\Course::orderBy('title')->get();
        
        // ดึง teacher ที่มี courses (สำหรับแสดงลายเซ็น)
        $teachers = \App\Models\User::where('role', 'teacher')
            ->whereHas('teachingCourses')
            ->orderBy('name')
            ->get();
        
        return view('admin.certificate-templates.preview', [
            'template' => $certificateTemplate,
            'courses' => $courses,
            'teachers' => $teachers,
        ]);
    }
}
