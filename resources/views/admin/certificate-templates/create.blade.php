@extends('layouts.app')

@section('title', 'สร้าง Certificate Template')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.certificate-templates.index') }}"
                class="mr-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    ✨ สร้าง Certificate Template ใหม่
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">ออกแบบ Template ใบประกาศนียบัตร</p>
            </div>
        </div>

        <form action="{{ route('admin.certificate-templates.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Basic Info -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">📝 ข้อมูลพื้นฐาน</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                ชื่อ Template <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                      bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                      focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="เช่น Classic Gold, Modern Blue">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                คำอธิบาย
                            </label>
                            <textarea name="description" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                         bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                         focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="อธิบายลักษณะของ Template">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Colors -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">🎨 สีและธีม</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                สีหลัก (Primary)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="primary_color" value="{{ old('primary_color', '#d4af37') }}"
                                    class="w-12 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                                <input type="text" id="primary_color_text" value="{{ old('primary_color', '#d4af37') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                    readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                สีกรอบ (Border)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="border_color" value="{{ old('border_color', '#d4af37') }}"
                                    class="w-12 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                                <input type="text" id="border_color_text" value="{{ old('border_color', '#d4af37') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                    readonly>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                สีตัวอักษร (Text)
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="text_color" value="{{ old('text_color', '#333333') }}"
                                    class="w-12 h-10 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                                <input type="text" id="text_color_text" value="{{ old('text_color', '#333333') }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                          bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">🖼️ รูปภาพ</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                โลโก้สถาบัน
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center hover:border-purple-500 transition-colors">
                                <input type="file" name="logo_image" accept="image/*" class="hidden" id="logo_input">
                                <label for="logo_input" class="cursor-pointer">
                                    <div id="logo_preview" class="hidden mb-2">
                                        <img src="" alt="Logo Preview" class="w-20 h-20 object-contain mx-auto">
                                    </div>
                                    <div id="logo_placeholder">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">คลิกเพื่อเลือกโลโก้</span>
                                    </div>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG ขนาดไม่เกิน 2MB</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                รูปพื้นหลัง (ถ้ามี)
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center hover:border-purple-500 transition-colors">
                                <input type="file" name="background_image" accept="image/*" class="hidden"
                                    id="bg_input">
                                <label for="bg_input" class="cursor-pointer">
                                    <div id="bg_preview" class="hidden mb-2">
                                        <img src="" alt="BG Preview"
                                            class="w-32 h-20 object-cover mx-auto rounded">
                                    </div>
                                    <div id="bg_placeholder">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">คลิกเพื่อเลือกพื้นหลัง</span>
                                    </div>
                                </label>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG ขนาดไม่เกิน 5MB (แนะนำ 1920x1080)</p>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">✍️ ลายเซ็น</h3>

                    <!-- Admin Signature -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 mb-4">
                        <h4 class="font-medium text-purple-800 dark:text-purple-200 mb-3">ลายเซ็นผู้อำนวยการ/Admin</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    รูปลายเซ็น
                                </label>
                                <div
                                    class="border-2 border-dashed border-purple-300 dark:border-purple-600 rounded-xl p-4 text-center hover:border-purple-500 transition-colors bg-white dark:bg-gray-800">
                                    <input type="file" name="admin_signature" accept="image/*" class="hidden"
                                        id="admin_sig_input">
                                    <label for="admin_sig_input" class="cursor-pointer">
                                        <div id="admin_sig_preview" class="hidden mb-2">
                                            <img src="" alt="Signature Preview"
                                                class="h-16 object-contain mx-auto">
                                        </div>
                                        <div id="admin_sig_placeholder">
                                            <svg class="w-10 h-10 text-purple-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            <span class="text-sm text-purple-500">อัพโหลดลายเซ็น</span>
                                        </div>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">แนะนำ: PNG พื้นหลังโปร่งใส</p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        ชื่อที่แสดง
                                    </label>
                                    <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                              focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="เช่น ดร.สมชาย ใจดี">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        ตำแหน่ง
                                    </label>
                                    <input type="text" name="admin_position" value="{{ old('admin_position') }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                              focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="เช่น ผู้อำนวยการศูนย์การเรียนรู้">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        ตำแหน่งลายเซ็น
                                    </label>
                                    <select name="admin_signature_position"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                               focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="left"
                                            {{ old('admin_signature_position') == 'left' ? 'selected' : '' }}>ซ้าย</option>
                                        <option value="right"
                                            {{ old('admin_signature_position') == 'right' ? 'selected' : '' }}>ขวา</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Signature -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-blue-800 dark:text-blue-200">ลายเซ็นผู้สอน</h4>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="show_teacher_signature" value="1"
                                    {{ old('show_teacher_signature', true) ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">แสดงลายเซ็นผู้สอน</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                ตำแหน่งลายเซ็นผู้สอน
                            </label>
                            <select name="teacher_signature_position"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl 
                                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                       focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="right"
                                    {{ old('teacher_signature_position') == 'right' ? 'selected' : '' }}>ขวา</option>
                                <option value="left"
                                    {{ old('teacher_signature_position') == 'left' ? 'selected' : '' }}>ซ้าย</option>
                            </select>
                        </div>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                            💡 ครูผู้สอนสามารถอัพโหลดลายเซ็นของตัวเองได้ในหน้าโปรไฟล์
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                <a href="{{ route('admin.certificate-templates.index') }}"
                    class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    สร้าง Template
                </button>
            </div>
        </form>
    </div>

    <script>
        // Color picker sync
        document.querySelectorAll('input[type="color"]').forEach(picker => {
            const textInput = document.getElementById(picker.name + '_text');
            picker.addEventListener('input', () => {
                if (textInput) textInput.value = picker.value;
            });
        });

        // Image preview
        function setupImagePreview(inputId, previewId, placeholderId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            if (input) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.querySelector('img').src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        }

        setupImagePreview('logo_input', 'logo_preview', 'logo_placeholder');
        setupImagePreview('bg_input', 'bg_preview', 'bg_placeholder');
        setupImagePreview('admin_sig_input', 'admin_sig_preview', 'admin_sig_placeholder');
    </script>
@endsection
