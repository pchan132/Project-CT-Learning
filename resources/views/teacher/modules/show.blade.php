<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('teacher.courses.modules.index', $course) }}"
                        class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $module->title }}</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            <a href="{{ route('teacher.courses.index') }}"
                                class="text-indigo-600 hover:text-indigo-900">คอร์สของฉัน</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('teacher.courses.show', $course) }}"
                                class="text-indigo-600 hover:text-indigo-900">{{ $course->title }}</a>
                            <span class="mx-2">/</span>
                            Module ที่ {{ $module->order }}
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>เพิ่มบทเรียน
                    </a>
                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>แก้ไข Module
                    </a>
                </div>
            </div>
        </div>

        <!-- Module Info Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $module->order }}</div>
                        <div class="text-sm text-gray-500">ลำดับ</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $lessons->count() }}</div>
                        <div class="text-sm text-gray-500">บทเรียนทั้งหมด</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $module->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">สร้างเมื่อ</div>
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

        <!-- Lessons List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">รายการบทเรียนใน Module</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    จัดการบทเรียนทั้งหมดใน "{{ $module->title }}"
                </p>
            </div>

            @if ($lessons->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($lessons as $lesson)
                        <li>
                            <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-green-600 font-semibold">{{ $lesson->order }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-medium text-gray-900">{{ $lesson->title }}</h3>
                                            <span
                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if ($lesson->content_type === 'PDF') bg-red-100 text-red-800
                                            @elseif($lesson->content_type === 'VIDEO') bg-purple-100 text-purple-800
                                            @elseif($lesson->content_type === 'TEXT') bg-gray-100 text-gray-800 @endif">
                                                {{ $lesson->content_type_label }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            บทเรียนที่ {{ $lesson->order }}
                                            @if ($lesson->content_url)
                                                @if ($lesson->isFileContent())
                                                    <span class="ml-2">• มีไฟล์แนบ</span>
                                                @elseif($lesson->isVideoContent())
                                                    <span class="ml-2">• วิดีโอ</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.courses.modules.lessons.show', [$course, $module, $lesson]) }}"
                                        class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>ดู
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>แก้ไข
                                    </a>
                                    <form
                                        action="{{ route('teacher.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}"
                                        method="POST" onsubmit="return confirm('คุณต้องการลบบทเรียนนี้ใช่หรือไม่?')">
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
                    <i class="fas fa-book-open text-gray-400 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มีบทเรียนใน Module นี้</h3>
                    <p class="text-gray-500 mb-4">เริ่มต้นโดยการสร้างบทเรียนแรกสำหรับ Module นี้</p>
                    <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>สร้างบทเรียนแรก
                    </a>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('teacher.courses.modules.index', $course) }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายการ Modules
            </a>
        </div>
    </div>
</x-app-layout>
