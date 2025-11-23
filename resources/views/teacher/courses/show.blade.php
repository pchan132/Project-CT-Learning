<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        <a href="{{ route('teacher.courses.index') }}"
                            class="text-indigo-600 hover:text-indigo-900">คอร์สของฉัน</a>
                        <span class="mx-2">/</span>
                        รายละเอียดคอร์ส
                    </p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                    </a>
                    <a href="{{ route('teacher.courses.edit', $course) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>แก้ไขคอร์ส
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Info Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-start">
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                            class="h-24 w-24 rounded-lg object-cover mr-6">
                    @else
                        <div class="h-24 w-24 bg-gray-200 rounded-lg flex items-center justify-center mr-6">
                            <i class="fas fa-book text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-xl font-medium text-gray-900 mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $course->total_modules }}</div>
                                <div class="text-sm text-gray-500">Modules</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $course->total_lessons }}</div>
                                <div class="text-sm text-gray-500">บทเรียน</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $course->enrollments->count() }}</div>
                                <div class="text-sm text-gray-500">นักเรียน</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $course->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">สร้างเมื่อ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Modules ในคอร์ส</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            จัดการ modules และบทเรียนทั้งหมดในคอร์สนี้
                        </p>
                    </div>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                    </a>
                </div>
            </div>

            @if ($course->modules->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($course->modules as $module)
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold">{{ $module->order }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $module->title }}</h4>
                                            <span
                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $module->total_lessons }} บทเรียน
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Module ที่ {{ $module->order }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>ดู
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                        class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-list mr-1"></i>บทเรียน
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>แก้ไข
                                    </a>
                                </div>
                            </div>

                            <!-- Show first few lessons in this module -->
                            @if ($module->lessons->count() > 0)
                                <div class="mt-4 ml-14">
                                    <div class="text-sm text-gray-500 mb-2">บทเรียนล่าสุด:</div>
                                    <div class="space-y-1">
                                        @foreach ($module->lessons->take(3) as $lesson)
                                            <div class="flex items-center text-sm">
                                                <div
                                                    class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                                    <span
                                                        class="text-gray-600 text-xs font-semibold">{{ $lesson->order }}</span>
                                                </div>
                                                <span class="text-gray-700">{{ $lesson->title }}</span>
                                                <span
                                                    class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium 
                                                @if ($lesson->content_type === 'PDF') bg-red-100 text-red-800
                                                @elseif($lesson->content_type === 'VIDEO') bg-purple-100 text-purple-800
                                                @elseif($lesson->content_type === 'TEXT') bg-gray-100 text-gray-800 @endif">
                                                    {{ $lesson->content_type }}
                                                </span>
                                            </div>
                                        @endforeach

                                        @if ($module->lessons->count() > 3)
                                            <div class="text-sm text-gray-500 mt-1">
                                                ... และอีก {{ $module->lessons->count() - 3 }} บทเรียน
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
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

        <!-- Statistics -->
        @if ($course->total_lessons > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-indigo-600">{{ $course->total_modules }}</div>
                            <div class="text-sm text-gray-500">Modules ทั้งหมด</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $course->total_lessons }}</div>
                            <div class="text-sm text-gray-500">บทเรียนทั้งหมด</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">
                                {{ $course->lessons()->where('content_type', 'PDF')->count() }}</div>
                            <div class="text-sm text-gray-500">บทเรียน PDF</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">
                                {{ $course->lessons()->where('content_type', 'VIDEO')->count() }}</div>
                            <div class="text-sm text-gray-500">บทเรียนวิดีโอ</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('teacher.courses.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายการคอร์ส
            </a>
        </div>
    </div>
</x-app-layout>
