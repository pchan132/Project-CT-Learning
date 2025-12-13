@extends('layouts.app')

@section('title', 'จัดการลายเซ็นสำหรับใบประกาศนียบัตร')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative"
                    role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upload Signature Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-upload mr-2 text-blue-500"></i>
                            อัปโหลดลายเซ็น
                        </h3>

                        <form action="{{ route('teacher.signature.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label for="signature_image"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    เลือกรูปภาพลายเซ็น
                                </label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="signature_image"
                                                class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>อัปโหลดไฟล์</span>
                                                <input id="signature_image" name="signature_image" type="file"
                                                    class="sr-only" accept="image/*" onchange="previewImage(event)">
                                            </label>
                                            <p class="pl-1">หรือลากไฟล์มาวาง</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, GIF สูงสุด 2MB
                                        </p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-4 hidden">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">ตัวอย่างรูปภาพ:</p>
                                    <img id="preview" src="" alt="Preview"
                                        class="max-w-full h-auto rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                </div>
                            </div>

                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    คำแนะนำสำหรับลายเซ็น
                                </h4>
                                <ul class="text-sm text-blue-700 dark:text-blue-400 space-y-1 ml-6 list-disc">
                                    <li class="text-red-500">กดบันทึกหลังจากอัปโหลดลายเซ็น ก่อนดูตัวอย่างใบประกาศนียบัตร
                                    </li>
                                    <li>ควรใช้ภาพลายเซ็นที่มีพื้นหลังโปร่งใส (PNG)</li>
                                    <li>ขนาดภาพแนะนำ: 300x100 พิกเซล หรือสัดส่วน 3:1</li>
                                    <li>ลายเซ็นควรชัดเจนและมีความละเอียดสูง</li>
                                    <li>ไม่ควรมีข้อความหรือตกแต่งเพิ่มเติม</li>
                                </ul>
                            </div>

                            <div class="flex justify-end gap-2 items-center ">
                                <!-- ปุ่มดูตัวอย่าง Certificate -->
                                <a href="{{ route('teacher.certificate-preview') }}"
                                    class="inline-flex items-center mt-3 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-certificate mr-2"></i>ดูตัวอย่างใบประกาศนียบัตร
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-500 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                                    <i class="fas fa-save mr-2"></i>
                                    บันทึกลายเซ็น
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Signature Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <i class="fas fa-signature mr-2 text-purple-500"></i>
                            ลายเซ็นปัจจุบัน
                        </h3>

                        @if ($teacher->signature_image)
                            <div class="space-y-4">
                                <div
                                    class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border-2 border-gray-200 dark:border-gray-600 flex items-center justify-center">
                                    <img src="{{ $teacher->signature_image_url }}" alt="ลายเซ็น" class="max-w-full h-auto"
                                        style="max-height: 150px;">
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('teacher.certificate-preview') }}"
                                        class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-green-600 dark:bg-green-500 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        ดูตัวอย่างใบประกาศฯ
                                    </a>

                                    <form action="{{ route('teacher.signature.delete') }}" method="POST"
                                        onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบลายเซ็นนี้?');" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 dark:bg-red-500 border border-transparent rounded-lg font-semibold text-white hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>
                                            ลบลายเซ็น
                                        </button>
                                    </form>
                                </div>

                                <div
                                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <p class="text-sm text-green-700 dark:text-green-400 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        ลายเซ็นของคุณพร้อมแสดงบนใบประกาศนียบัตรแล้ว
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                    <i class="fas fa-signature text-4xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 mb-2">ยังไม่มีลายเซ็น</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">
                                    กรุณาอัปโหลดลายเซ็นเพื่อใช้บนใบประกาศนียบัตร
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div
                class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 overflow-hidden shadow-sm sm:rounded-lg border border-purple-200 dark:border-purple-800">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-purple-900 dark:text-purple-300 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i>
                        ทำไมต้องมีลายเซ็นบนใบประกาศนียบัตร?
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                            <div class="text-center mb-3">
                                <i class="fas fa-certificate text-3xl text-purple-500"></i>
                            </div>
                            <h4 class="font-semibold text-center mb-2 text-gray-900 dark:text-gray-100">ความน่าเชื่อถือ
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                ลายเซ็นของผู้สอนช่วยเพิ่มความน่าเชื่อถือให้กับใบประกาศนียบัตร
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                            <div class="text-center mb-3">
                                <i class="fas fa-shield-alt text-3xl text-green-500"></i>
                            </div>
                            <h4 class="font-semibold text-center mb-2 text-gray-900 dark:text-gray-100">การรับรอง</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                แสดงว่าผู้สอนได้รับรองผลการเรียนของนักเรียนอย่างเป็นทางการ
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                            <div class="text-center mb-3">
                                <i class="fas fa-award text-3xl text-yellow-500"></i>
                            </div>
                            <h4 class="font-semibold text-center mb-2 text-gray-900 dark:text-gray-100">
                                ความเป็นมืออาชีพ</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                ทำให้ใบประกาศนียบัตรดูเป็นทางการและมีมาตรฐาน
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
