<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('teacher.courses.modules.index', $course) }}"
                    class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">สร้าง Module ใหม่</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        คอร์ส: {{ $course->title }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <form action="{{ route('teacher.courses.modules.store', $course) }}" method="POST">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded m-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-4 py-5 sm:p-6">
                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            ชื่อ Module <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror"
                            placeholder="เช่น บทที่ 1: พื้นฐานการเขียนโปรแกรม" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            ลำดับที่ <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $nextOrder) }}"
                            min="1"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('order') border-red-500 @enderror"
                            required>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            ลำดับการแสดงผลของ Module ในคอร์ส (ลำดับถัดไป: {{ $nextOrder }})
                        </p>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">ข้อมูลคอร์ส</h3>
                        <div class="flex items-center">
                            @if ($course->cover_image_url)
                                <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                    alt="{{ $course->title }}" class="h-12 w-12 rounded-lg object-cover mr-3">
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $course->title }}</p>
                                <p class="text-sm text-gray-500">{{ $course->modules->count() }} modules ในคอร์สนี้</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="{{ route('teacher.courses.modules.index', $course) }}"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>สร้าง Module
                    </button>
                </div>
            </form>
        </div>
</x-app-layout>
