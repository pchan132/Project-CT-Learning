<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <!-- Course Cover Image -->
            @if ($course->cover_image_url)
                <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600">
                    <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                        class="w-full h-full object-cover">
                </div>
            @else
                <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
            @endif

            <div class="p-8">
                <!-- Course Title and Teacher -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            ผู้สอน: {{ $course->teacher->name }}
                        </div>
                    </div>

                    <!-- Unenroll Button -->
                    <form action="{{ route('student.courses.unenroll', $course) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะถอนการลงทะเบียนคอร์สนี้?')"
                            class="bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            ถอนการลงทะเบียน
                        </button>
                    </form>
                </div>

                <!-- Course Description -->
                <p class="text-gray-700 mb-6">{{ $course->description }}</p>

                <!-- Course Stats and Progress -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $course->total_modules }}</div>
                        <div class="text-sm text-gray-600">โมดูล</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $course->total_lessons }}</div>
                        <div class="text-sm text-gray-600">บทเรียนทั้งหมด</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ count($completedLessons) }}</div>
                        <div class="text-sm text-gray-600">บทเรียนที่เรียนเสร็จ</div>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $progress }}%</div>
                        <div class="text-sm text-gray-600">ความคืบหน้า</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="bg-gray-100 rounded-lg p-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>ความคืบหน้าโดยรวม</span>
                        <span class="font-semibold">{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                            style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Modules and Lessons -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">เนื้อหาคอร์ส</h2>

                @if ($course->modules->count() > 0)
                    <div class="space-y-6">
                        @foreach ($course->modules as $module)
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                                <!-- Module Header -->
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        โมดูล {{ $module->order }}: {{ $module->title }}
                                    </h3>
                                </div>

                                <!-- Module Description -->
                                @if ($module->description)
                                    <div class="px-6 py-3 bg-blue-50 border-b border-gray-200">
                                        <p class="text-sm text-gray-700">{{ $module->description }}</p>
                                    </div>
                                @endif

                                <!-- Lessons List -->
                                <div class="divide-y divide-gray-200">
                                    @foreach ($module->lessons->sortBy('order') as $lesson)
                                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center flex-1">
                                                    <!-- Lesson Status Icon -->
                                                    @if (in_array($lesson->id, $completedLessons))
                                                        <div class="mr-3">
                                                            <div
                                                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-green-600" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mr-3">
                                                            <div
                                                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Lesson Info -->
                                                    <div class="flex-1">
                                                        <h4 class="text-gray-900 font-medium mb-1">{{ $lesson->title }}
                                                        </h4>
                                                        <div class="flex items-center text-sm text-gray-500 space-x-3">
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                                {{ $lesson->content_type_label }}
                                                            </span>
                                                            <span>บทเรียนที่ {{ $lesson->order }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Action Button -->
                                                <a href="{{ route('student.courses.learn-lesson', [$course, $lesson]) }}"
                                                    class="ml-4 {{ in_array($lesson->id, $completedLessons) ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }} px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium">
                                                    @if (in_array($lesson->id, $completedLessons))
                                                        เรียนซ้ำ
                                                    @else
                                                        เริ่มเรียน
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">ยังไม่มีเนื้อหาในคอร์สนี้</h3>
                        <p class="text-gray-600">ผู้สอนกำลังเพิ่มเนื้อหาในคอร์สนี้</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">สถิติการเรียน</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">บทเรียนที่เรียนเสร็จ</span>
                            <span class="font-semibold text-green-600">{{ count($completedLessons) }} /
                                {{ $course->total_lessons }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">บทเรียนที่เหลือ</span>
                            <span
                                class="font-semibold text-orange-600">{{ $course->total_lessons - count($completedLessons) }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">ความคืบหน้า</span>
                            <span class="font-semibold text-blue-600">{{ $progress }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Next Lesson -->
                @if ($course->lessons->count() > 0)
                    @php
                        $nextLesson = $course
                            ->lessons()
                            ->whereNotIn('id', $completedLessons)
                            ->orderBy('order')
                            ->first();
                    @endphp

                    @if ($nextLesson)
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
                            <h3 class="text-lg font-semibold mb-3">บทเรียนถัดไป</h3>
                            <h4 class="font-medium mb-2">{{ $nextLesson->title }}</h4>
                            <p class="text-sm opacity-90 mb-4">{{ $nextLesson->content_type_label }}</p>
                            <a href="{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}"
                                class="w-full bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-center font-medium inline-block">
                                เริ่มเรียนต่อ
                            </a>
                        </div>
                    @else
                        <div
                            class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-md p-6 text-white">
                            <h3 class="text-lg font-semibold mb-3">🎉 ยินดีด้วย!</h3>
                            <p class="text-sm opacity-90">คุณได้เรียนคอร์สนี้เสร็จสมบูรณ์แล้ว</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
