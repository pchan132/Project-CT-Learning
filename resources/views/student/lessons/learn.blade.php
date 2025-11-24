<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Lesson Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <!-- Breadcrumb -->
                    <nav class="flex text-sm text-gray-500">
                        <a href="{{ route('student.courses.index') }}" class="hover:text-gray-700">คอร์สเรียน</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('student.courses.show', $course) }}"
                            class="hover:text-gray-700">{{ $course->title }}</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-900">{{ $lesson->title }}</span>
                    </nav>

                    <!-- Completion Status -->
                    @if ($isCompleted)
                        <div
                            class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            เรียนเสร็จแล้ว
                        </div>
                    @endif
                </div>

                <!-- Lesson Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $lesson->title }}</h1>

                <!-- Lesson Meta -->
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        โมดูล {{ $lesson->module->order }}: {{ $lesson->module->title }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        {{ $lesson->content_type_label }}
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        บทเรียนที่ {{ $lesson->order }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <!-- Content based on type -->
                    @switch($lesson->content_type)
                        @case('TEXT')
                            <div class="prose max-w-none">
                                <div class="whitespace-pre-wrap text-gray-800 leading-relaxed">
                                    {!! nl2br(e($lesson->content_text)) !!}
                                </div>
                            </div>
                        @break

                        @case('VIDEO')
                            @if ($lesson->content_url)
                                <div class="aspect-w-16 aspect-h-9 mb-6">
                                    <div class="bg-gray-900 rounded-lg overflow-hidden">
                                        <video controls class="w-full" poster="{{ asset('images/video-poster.jpg') }}">
                                            <source src="{{ $lesson->content_url }}" type="video/mp4">
                                            <source src="{{ $lesson->content_url }}" type="video/webm">
                                            บราวเซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                                        </video>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600">ไม่พบไฟล์วิดีโอ</p>
                                </div>
                            @endif
                        @break

                        @case('PDF')
                            @if ($lesson->content_url)
                                <div class="text-center py-8">
                                    <div class="mb-6">
                                        <svg class="w-24 h-24 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">เอกสาร PDF</h3>
                                        <p class="text-gray-600 mb-4">คลิกที่ปุ่มด้านล่างเพื่อเปิดอ่านเอกสาร</p>
                                    </div>
                                    <a href="{{ $lesson->content_display_url }}" target="_blank"
                                        class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        เปิดเอกสาร PDF
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600">ไม่พบไฟล์ PDF</p>
                                </div>
                            @endif
                        @break

                        @case('PPT')
                            @if ($lesson->content_url)
                                <div class="text-center py-8">
                                    <div class="mb-6">
                                        <svg class="w-24 h-24 text-orange-500 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">งานนำเสนอ PowerPoint</h3>
                                        <p class="text-gray-600 mb-4">คลิกที่ปุ่มด้านล่างเพื่อดาวน์โหลดหรือเปิดงานนำเสนอ</p>
                                    </div>
                                    <a href="{{ $lesson->content_display_url }}" target="_blank"
                                        class="inline-flex items-center bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        เปิดงานนำเสนอ
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600">ไม่พบไฟล์ PowerPoint</p>
                                </div>
                            @endif
                        @break

                        @default
                            <div class="text-center py-12 bg-gray-50 rounded-lg">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-gray-600">ไม่สามารถแสดงเนื้อหาประเภทนี้ได้</p>
                            </div>
                    @endswitch

                    <!-- Complete Lesson Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        @if (!$isCompleted)
                            <button id="completeLessonBtn" onclick="completeLesson()"
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center justify-center font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                ทำเครื่องหมายว่าเรียนเสร็จ
                            </button>
                        @else
                            <div
                                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    คุณได้เรียนบทเรียนนี้เสร็จสมบูรณ์แล้ว
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Lesson Navigation -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">การนำทางบทเรียน</h3>

                    <div class="space-y-3">
                        @if ($previousLesson)
                            <a href="{{ route('student.courses.learn-lesson', [$course, $previousLesson]) }}"
                                class="flex items-center justify-between w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    บทเรียนก่อนหน้า
                                </div>
                            </a>
                        @endif

                        @if ($nextLesson)
                            <a href="{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}"
                                class="flex items-center justify-between w-full bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg transition-colors duration-200">
                                <span>บทเรียนถัดไป</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif

                        <a href="{{ route('student.courses.show', $course) }}"
                            class="flex items-center justify-center w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            กลับไปหน้าคอร์ส
                        </a>
                    </div>
                </div>

                <!-- Course Progress -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ความคืบหน้าคอร์ส</h3>

                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>ความคืบหน้าโดยรวม</span>
                            <span class="font-semibold">{{ $course->getProgressForStudent(auth()->id()) }}%</span>
                        </div>
                        <div class="w-full bg-gray-300 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $course->getProgressForStudent(auth()->id()) }}%"></div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600">
                        <div class="flex justify-between mb-1">
                            <span>บทเรียนที่เรียนเสร็จ:</span>
                            <span class="font-medium">{{ $course->getCompletedLessonsCount(auth()->id()) }} /
                                {{ $course->getTotalLessonsAttribute() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>บทเรียนที่เหลือ:</span>
                            <span
                                class="font-medium">{{ $course->getTotalLessonsAttribute() - $course->getCompletedLessonsCount(auth()->id()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function completeLesson() {
            const btn = document.getElementById('completeLessonBtn');
            const originalText = btn.innerHTML;

            // Show loading state
            btn.disabled = true;
            btn.innerHTML =
                '<svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>กำลังบันทึก...';

            // Send AJAX request
            fetch(`{{ route('student.courses.complete-lesson', [$course, $lesson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success state
                        btn.innerHTML =
                            '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>เรียนเสร็จแล้ว!';
                        btn.className =
                            'w-full bg-green-100 text-green-700 px-6 py-3 rounded-lg flex items-center justify-center font-medium border border-green-400';

                        // Update progress bar if exists
                        const progressBar = document.querySelector('.bg-gradient-to-r');
                        if (progressBar && data.progress !== undefined) {
                            progressBar.style.width = data.progress + '%';
                        }

                        // Show success message
                        showNotification('บทเรียนนี้เรียนเสร็จสมบูรณ์แล้ว!', 'success');

                        // Redirect to next lesson after 2 seconds if available
                        @if ($nextLesson)
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}';
                            }, 2000);
                        @endif
                    } else {
                        throw new Error(data.message || 'เกิดข้อผิดพลาด');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showNotification('เกิดข้อผิดพลาด กรุณาลองใหม่', 'error');
                });
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-100 text-green-700 border border-green-400' : 
        'bg-red-100 text-red-700 border border-red-400'
    }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</x-app-layout>
