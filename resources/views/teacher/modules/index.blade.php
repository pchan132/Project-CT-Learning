<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">จัดการ Modules - {{ $course->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        <a href="{{ route('teacher.courses.index') }}"
                            class="text-indigo-600 hover:text-indigo-900">คอร์สของฉัน</a>
                        <span class="mx-2">/</span>
                        {{ $course->title }}
                    </p>
                </div>
                <a href="{{ route('teacher.courses.modules.create', $course) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                </a>
            </div>
        </div>

        <!-- Course Info Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                            class="h-16 w-16 rounded-lg object-cover mr-4">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-500">{{ Str::limit($course->description, 100) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Modules List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if ($modules->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($modules as $module)
                        <li>
                            <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold">{{ $module->order }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $module->title }}</h3>
                                            <span
                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $module->total_lessons }} บทเรียน
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Module ที่ {{ $module->order }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-list mr-1"></i>ดูบทเรียน
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>แก้ไข
                                    </a>
                                    <form action="{{ route('teacher.courses.modules.destroy', [$course, $module]) }}"
                                        method="POST" onsubmit="return confirm('คุณต้องการลบ Module นี้ใช่หรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>ลบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มี Modules</h3>
                    <p class="text-gray-500 mb-4">เริ่มต้นโดยการสร้าง Module แรกสำหรับคอร์สนี้</p>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>สร้าง Module แรก
                    </a>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('teacher.courses.show', $course) }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายละเอียดคอร์ส
            </a>
        </div>
    </div>
</x-app-layout>
