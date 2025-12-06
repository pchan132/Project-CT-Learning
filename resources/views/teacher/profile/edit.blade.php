@extends('layouts.app')

@section('title', 'แก้ไขโปรไฟล์อาจารย์')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-user-edit text-blue-500 mr-3"></i>
                แก้ไขโปรไฟล์อาจารย์
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                อัปเดตข้อมูลและรูปโปรไฟล์ของคุณ
            </p>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Profile Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Current Profile Image -->
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600">
                    <div class="flex flex-col items-center">
                        <div class="relative group">
                            @if ($teacher->profile_image_url)
                                <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->name }}" id="preview-image"
                                    class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl">
                            @else
                                <div id="default-avatar"
                                    class="w-40 h-40 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/50">
                                    <span class="text-5xl font-bold text-white">{{ $teacher->initials }}</span>
                                </div>
                                <img src="" alt="" id="preview-image"
                                    class="w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl hidden">
                            @endif

                            <!-- Upload Overlay -->
                            <label for="profile_image"
                                class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <div class="text-center text-white">
                                    <i class="fas fa-camera text-2xl mb-1"></i>
                                    <div class="text-xs">เปลี่ยนรูป</div>
                                </div>
                            </label>
                        </div>

                        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden"
                            onchange="previewImage(this)">

                        <p class="mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            รองรับไฟล์ JPG, PNG, GIF, WebP ขนาดไม่เกิน 2MB
                        </p>

                        @if ($teacher->profile_image)
                            <button type="button" onclick="confirmDeleteImage()"
                                class="mt-2 text-white/80 hover:text-white text-sm underline">
                                <i class="fas fa-trash mr-1"></i>ลบรูปโปรไฟล์
                            </button>
                        @endif
                    </div>
                </div>

                @error('profile_image')
                    <div class="px-6 pt-4">
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    </div>
                @enderror

                <div class="p-6 space-y-6">
                    <!-- Name (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ชื่อ-นามสกุล
                        </label>
                        <input type="text" value="{{ $teacher->name }}" disabled
                            class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300">
                        <p class="mt-1 text-xs text-gray-500">หากต้องการเปลี่ยนชื่อ กรุณาไปที่หน้าตั้งค่าโปรไฟล์</p>
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ตำแหน่ง
                        </label>
                        <input type="text" name="position" id="position"
                            value="{{ old('position', $teacher->position) }}"
                            placeholder="เช่น หัวหน้าแผนกวิชา, ครูประจำ, ครูพิเศษสอน"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('position') border-red-500 @enderror">
                        @error('position')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ประวัติย่อ / แนะนำตัว
                        </label>
                        <textarea name="bio" id="bio" rows="4"
                            placeholder="เขียนแนะนำตัวเองสั้นๆ เพื่อให้นักเรียนรู้จักคุณมากขึ้น..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none @error('bio') border-red-500 @enderror">{{ old('bio', $teacher->bio) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <span id="bio-count">{{ strlen($teacher->bio ?? '') }}</span>/1000 ตัวอักษร
                        </p>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Signature Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                            <i class="fas fa-signature text-purple-500 mr-2"></i>ลายเซ็นสำหรับใบประกาศนียบัตร
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            ลายเซ็นของคุณจะแสดงในใบประกาศนียบัตรของนักเรียนที่เรียนจบคอร์สของคุณ
                        </p>

                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
                            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                                <!-- Current Signature Preview -->
                                <div class="flex-shrink-0">
                                    @if ($teacher->signature_image)
                                        <div class="relative group">
                                            <img src="{{ $teacher->signature_image_url }}" alt="ลายเซ็นปัจจุบัน"
                                                id="signature-preview"
                                                class="h-20 bg-white rounded-lg border border-purple-200 p-2">
                                            <div class="text-xs text-center text-purple-600 mt-1">ลายเซ็นปัจจุบัน</div>
                                        </div>
                                    @else
                                        <div class="w-48 h-20 bg-white dark:bg-gray-700 rounded-lg border-2 border-dashed border-purple-300 dark:border-purple-600 flex items-center justify-center"
                                            id="signature-placeholder">
                                            <div class="text-center text-purple-400">
                                                <i class="fas fa-signature text-2xl mb-1"></i>
                                                <div class="text-xs">ยังไม่มีลายเซ็น</div>
                                            </div>
                                        </div>
                                        <img src="" alt="" id="signature-preview"
                                            class="h-20 bg-white rounded-lg border border-purple-200 p-2 hidden">
                                    @endif
                                </div>

                                <!-- Upload Section -->
                                <div class="flex-1">
                                    <label for="signature_image"
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg cursor-pointer transition-colors">
                                        <i class="fas fa-upload mr-2"></i>
                                        {{ $teacher->signature_image ? 'เปลี่ยนลายเซ็น' : 'อัพโหลดลายเซ็น' }}
                                    </label>
                                    <input type="file" name="signature_image" id="signature_image" accept="image/*"
                                        class="hidden" onchange="previewSignature(this)">

                                    <p class="mt-2 text-xs text-purple-600 dark:text-purple-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        แนะนำ: ใช้รูป PNG พื้นหลังโปร่งใส ขนาดไม่เกิน 2MB
                                    </p>

                                    <!-- ปุ่มดูตัวอย่าง Certificate -->
                                    <a href="{{ route('teacher.certificate-preview') }}"
                                        class="inline-flex items-center mt-3 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors">
                                        <i class="fas fa-certificate mr-2"></i>ดูตัวอย่างใบประกาศนียบัตร
                                    </a>
                                </div>
                            </div>
                        </div>

                        @error('signature_image')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-between">
                    <a href="{{ route('teacher.dashboard') }}"
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>กลับ
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Image Form (Hidden) -->
        <form id="delete-image-form" action="{{ route('teacher.profile.delete-image') }}" method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    const defaultAvatar = document.getElementById('default-avatar');

                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    if (defaultAvatar) {
                        defaultAvatar.classList.add('hidden');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function confirmDeleteImage() {
            if (confirm('คุณต้องการลบรูปโปรไฟล์ใช่หรือไม่?')) {
                document.getElementById('delete-image-form').submit();
            }
        }

        // Bio character count
        const bioTextarea = document.getElementById('bio');
        const bioCount = document.getElementById('bio-count');

        bioTextarea.addEventListener('input', function() {
            bioCount.textContent = this.value.length;

            if (this.value.length > 1000) {
                bioCount.classList.add('text-red-500');
            } else {
                bioCount.classList.remove('text-red-500');
            }
        });

        // Preview signature image
        function previewSignature(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('signature-preview');
                    const placeholder = document.getElementById('signature-placeholder');

                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
