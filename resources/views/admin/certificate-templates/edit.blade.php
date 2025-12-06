@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">
                            <i class="fas fa-certificate text-yellow-500 mr-2"></i>
                            แก้ไข Template: {{ $template->name }}
                        </h2>
                        <a href="{{ route('admin.certificate-templates.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            ← กลับ
                        </a>
                    </div>

                    <form action="{{ route('admin.certificate-templates.update', $template) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- ข้อมูลพื้นฐาน --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-info-circle mr-2"></i>ข้อมูลพื้นฐาน
                            </h3>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium mb-1">ชื่อ Template <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name"
                                        value="{{ old('name', $template->name) }}"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium mb-1">คำอธิบาย</label>
                                    <textarea name="description" id="description" rows="2"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $template->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- โลโก้ --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-image mr-2"></i>โลโก้สถาบัน
                            </h3>

                            <div>
                                @if ($template->logo_image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $template->logo_image) }}" alt="Logo"
                                            class="h-20 object-contain bg-white p-2 rounded-lg border">
                                        <p class="text-xs text-gray-500 mt-1">โลโก้ปัจจุบัน</p>
                                    </div>
                                @endif
                                <input type="file" name="logo_image" id="logo_image" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-xs text-gray-500 mt-1">แนะนำ: PNG พื้นหลังโปร่งใส ขนาดไม่เกิน 2MB</p>
                                @error('logo_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ลายเซ็นผู้อำนวยการ/ผู้บริหาร --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-signature mr-2"></i>ลายเซ็นผู้อำนวยการ/ผู้บริหาร
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- รูปลายเซ็น --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">รูปลายเซ็น</label>
                                    @if ($template->admin_signature)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $template->admin_signature) }}"
                                                alt="Admin Signature"
                                                class="h-16 object-contain bg-white p-2 rounded-lg border">
                                            <p class="text-xs text-gray-500 mt-1">ลายเซ็นปัจจุบัน</p>
                                        </div>
                                    @endif
                                    <input type="file" name="admin_signature" id="admin_signature" accept="image/*"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <p class="text-xs text-gray-500 mt-1">แนะนำ: PNG พื้นหลังโปร่งใส</p>
                                    @error('admin_signature')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- ข้อมูลผู้ลงนาม --}}
                                <div class="space-y-4">
                                    <div>
                                        <label for="admin_name" class="block text-sm font-medium mb-1">ชื่อผู้ลงนาม</label>
                                        <input type="text" name="admin_name" id="admin_name"
                                            value="{{ old('admin_name', $template->admin_name) }}"
                                            placeholder="เช่น ดร.สมชาย ใจดี"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="admin_position" class="block text-sm font-medium mb-1">ตำแหน่ง</label>
                                        <input type="text" name="admin_position" id="admin_position"
                                            value="{{ old('admin_position', $template->admin_position) }}"
                                            placeholder="เช่น ผู้อำนวยการสถาบัน"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="admin_signature_position"
                                            class="block text-sm font-medium mb-1">ตำแหน่งลายเซ็นบน Certificate</label>
                                        <select name="admin_signature_position" id="admin_signature_position"
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="right"
                                                {{ old('admin_signature_position', $template->admin_signature_position) == 'right' ? 'selected' : '' }}>
                                                ขวา</option>
                                            <option value="left"
                                                {{ old('admin_signature_position', $template->admin_signature_position) == 'left' ? 'selected' : '' }}>
                                                ซ้าย</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ลายเซ็นครูผู้สอน --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-4 text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>ลายเซ็นครูผู้สอน
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="show_teacher_signature" value="1"
                                            {{ old('show_teacher_signature', $template->show_teacher_signature) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                                        <span class="text-sm font-medium">แสดงลายเซ็นครูผู้สอนในใบ Certificate</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2 ml-8">
                                        ครูผู้สอนสามารถอัพโหลดลายเซ็นได้ในหน้าโปรไฟล์ของตัวเอง
                                    </p>
                                </div>

                                <div>
                                    <label for="teacher_signature_position"
                                        class="block text-sm font-medium mb-1">ตำแหน่งลายเซ็นครู</label>
                                    <select name="teacher_signature_position" id="teacher_signature_position"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="left"
                                            {{ old('teacher_signature_position', $template->teacher_signature_position) == 'left' ? 'selected' : '' }}>
                                            ซ้าย</option>
                                        <option value="right"
                                            {{ old('teacher_signature_position', $template->teacher_signature_position) == 'right' ? 'selected' : '' }}>
                                            ขวา</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">หากลายเซ็นผู้บริหารอยู่ขวา
                                        แนะนำให้ลายเซ็นครูอยู่ซ้าย</p>
                                </div>
                            </div>
                        </div>

                        {{-- สถานะ --}}
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-6 mb-6">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500 w-5 h-5">
                                <span class="text-sm font-medium">ใช้งาน Template นี้เป็นค่าเริ่มต้น</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2 ml-8">
                                เมื่อเลือก จะยกเลิกการใช้งาน Template อื่นที่เป็นค่าเริ่มต้นอยู่
                            </p>
                        </div>

                        {{-- Hidden fields สำหรับค่าสีที่ required --}}
                        <input type="hidden" name="border_color" value="{{ $template->border_color ?? '#ca8a04' }}">
                        <input type="hidden" name="primary_color" value="{{ $template->primary_color ?? '#2563eb' }}">
                        <input type="hidden" name="text_color" value="{{ $template->text_color ?? '#1f2937' }}">

                        {{-- ปุ่มบันทึก --}}
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.certificate-templates.preview', $template) }}"
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition"
                                target="_blank">
                                <i class="fas fa-eye mr-2"></i>ดูตัวอย่าง Certificate
                            </a>

                            <div class="flex space-x-4">
                                <a href="{{ route('admin.certificate-templates.index') }}"
                                    class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-semibold transition">
                                    ยกเลิก
                                </a>
                                <button type="submit"
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                                    <i class="fas fa-save mr-2"></i>บันทึกการแก้ไข
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
