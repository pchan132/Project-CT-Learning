@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        รายละเอียดคอร์ส
    </h2>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('student.courses.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                กลับไปหน้าคอร์สเรียน
            </a>
        </div>

        <!-- Course Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Course Cover Image -->
            @if ($course->cover_image_url)
                <div class="h-72 relative">
                    <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $course->title }}</h1>
                    </div>
                </div>
            @else
                <div class="h-72 bg-gradient-to-br from-blue-500 to-purple-600 relative flex items-center justify-center">
                    <svg class="w-32 h-32 text-white opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <div class="absolute bottom-6 left-6 right-6">
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $course->title }}</h1>
                    </div>
                </div>
            @endif

            <!-- Course Info -->
            <div class="p-8">
                <!-- Teacher Info Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-8 border border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        ผู้สอน
                    </h3>
                    <div class="flex items-center">
                        <!-- Teacher Avatar -->
                        <div
                            class="w-20 h-20 rounded-full overflow-hidden bg-gradient-to-br from-blue-400 to-purple-500 flex-shrink-0 shadow-lg">
                            @if ($course->teacher->profile_image)
                                <img src="{{ asset('storage/' . $course->teacher->profile_image) }}"
                                    alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-5">
                            <h4 class="text-xl font-bold text-gray-900">{{ $course->teacher->name }}</h4>
                            <p class="text-gray-600">{{ $course->teacher->email }}</p>
                            @if ($course->teacher->bio)
                                <p class="text-sm text-gray-500 mt-1">{{ $course->teacher->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Course Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">เกี่ยวกับคอร์สนี้</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $course->description }}</p>
                </div>

                <!-- Course Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-100">
                        <div class="text-3xl font-bold text-blue-600">{{ $course->total_modules }}</div>
                        <div class="text-sm text-gray-600 mt-1">โมดูล</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center border border-green-100">
                        <div class="text-3xl font-bold text-green-600">{{ $course->total_lessons }}</div>
                        <div class="text-sm text-gray-600 mt-1">บทเรียน</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center border border-purple-100">
                        <div class="text-3xl font-bold text-purple-600">{{ $course->quizCount ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">แบบทดสอบ</div>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4 text-center border border-orange-100">
                        <div class="text-3xl font-bold text-orange-600">{{ $course->enrollments_count ?? 0 }}</div>
                        <div class="text-sm text-gray-600 mt-1">ผู้เรียน</div>
                    </div>
                </div>

                <!-- Course Content Preview -->
                @if ($course->modules->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">เนื้อหาในคอร์ส</h3>
                        <div class="space-y-3">
                            @foreach ($course->modules as $module)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-blue-600 font-bold">{{ $module->order }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $module->title }}</h4>
                                            <p class="text-sm text-gray-500">{{ $module->lessons->count() }} บทเรียน</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Enroll Button -->
                <div class="border-t border-gray-200 pt-6">
                    <button type="button" onclick="openEnrollModal()"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 flex items-center justify-center text-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        ลงทะเบียนเรียนคอร์สนี้
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Confirmation Modal -->
    <div id="enrollModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeEnrollModal()"></div>

            <!-- Modal panel -->
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900" id="modal-title">ยืนยันการลงทะเบียน</h3>
                        <p class="mt-2 text-gray-600">คุณต้องการลงทะเบียนเรียนคอร์สนี้หรือไม่?</p>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-gray-50 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-gray-900 text-lg mb-4">{{ $course->title }}</h4>

                        <!-- Teacher Info with Photo -->
                        <div class="flex items-center bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                            <!-- Teacher Avatar -->
                            <div
                                class="w-16 h-16 rounded-full overflow-hidden bg-gradient-to-br from-blue-400 to-purple-500 flex-shrink-0 shadow-md ring-4 ring-white">
                                @if ($course->teacher->profile_image)
                                    <img src="{{ asset('storage/' . $course->teacher->profile_image) }}"
                                        alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center text-white text-xl font-bold">
                                        {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">ผู้สอน</p>
                                <h5 class="text-lg font-bold text-gray-900">{{ $course->teacher->name }}</h5>
                                <p class="text-sm text-gray-600">{{ $course->teacher->email }}</p>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <div class="text-center bg-white rounded-lg py-3 border border-gray-200">
                                <div class="text-xl font-bold text-blue-600">{{ $course->total_modules }}</div>
                                <div class="text-xs text-gray-500">โมดูล</div>
                            </div>
                            <div class="text-center bg-white rounded-lg py-3 border border-gray-200">
                                <div class="text-xl font-bold text-green-600">{{ $course->total_lessons }}</div>
                                <div class="text-xs text-gray-500">บทเรียน</div>
                            </div>
                            <div class="text-center bg-white rounded-lg py-3 border border-gray-200">
                                <div class="text-xl font-bold text-purple-600">{{ $course->quizCount ?? 0 }}</div>
                                <div class="text-xs text-gray-500">แบบทดสอบ</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeEnrollModal()"
                        class="px-5 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        ยกเลิก
                    </button>
                    <form action="{{ route('student.courses.enroll', $course) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all font-medium flex items-center shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            ยืนยันลงทะเบียน
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEnrollModal() {
            document.getElementById('enrollModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEnrollModal() {
            document.getElementById('enrollModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEnrollModal();
            }
        });
    </script>
@endsection
