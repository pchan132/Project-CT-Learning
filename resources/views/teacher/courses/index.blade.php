@extends('layouts.app')

@section('title', 'รายวิชาของฉัน')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-book text-indigo-500 mr-2"></i>รายวิชาของฉัน
                </h1>
                <p class="mt-1 text-gray-600 dark:text-gray-400">
                    จัดการรายวิชา โมดูล และบทเรียนของคุณ
                </p>
            </div>
            <a href="{{ route('teacher.courses.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg inline-flex items-center shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-plus mr-2"></i>
                สร้างรายวิชาใหม่
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <form action="{{ route('teacher.courses.index') }}" method="GET" class="flex gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ค้นหารายวิชา..."
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-colors">
                    <i class="fas fa-search mr-2"></i>ค้นหา
                </button>
                @if ($search)
                    <a href="{{ route('teacher.courses.index') }}"
                        class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors">
                        <i class="fas fa-times mr-2"></i>ล้าง
                    </a>
                @endif
            </form>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl"
                role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Results Info -->
        @if ($search)
            <div class="mb-4 text-gray-600 dark:text-gray-400">
                <i class="fas fa-filter mr-1"></i>
                พบ {{ $courses->total() }} รายวิชา สำหรับ "<strong>{{ $search }}</strong>"
            </div>
        @endif

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Course Image -->
                    <div class="relative h-48 bg-gradient-to-br from-indigo-500 to-purple-600">
                        @if ($course->cover_image_url)
                            <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-book text-6xl text-white/50"></i>
                            </div>
                        @endif

                        <!-- Stats Badges -->
                        <div class="absolute top-3 left-3 flex gap-2">
                            <span
                                class="bg-white/90 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2.5 py-1 rounded-lg shadow">
                                นักเรียน
                                <i class="fas fa-users mr-1 text-green-500"></i>{{ $course->enrollments_count ?? 0 }}
                            </span>
                            <span
                                class="bg-white/90 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2.5 py-1 rounded-lg shadow">
                                โมดูล
                                <i class="fas fa-folder mr-1 text-blue-500"></i>{{ $course->modules_count ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">
                            {{ $course->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                            {{ $course->description ?? 'ไม่มีคำอธิบาย' }}
                        </p>

                        <!-- Course Stats -->
                        <div class="flex items-center space-x-4 mb-4 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center">
                                <i class="fas fa-folder mr-1.5 text-indigo-500"></i>
                                {{ $course->modules_count ?? $course->modules->count() }} โมดูล
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-1.5 text-green-500"></i>
                                {{ $course->modules->sum(fn($m) => $m->lessons->count()) }} บทเรียน
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('teacher.courses.students', $course) }}"
                                class="text-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-users mr-1"></i>นักเรียน
                            </a>
                            <a href="{{ route('teacher.courses.modules.index', $course) }}"
                                class="text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-folder mr-1"></i>โมดูล
                            </a>
                            <a href="{{ route('teacher.courses.edit', $course) }}"
                                class="text-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-edit mr-1"></i>แก้ไข
                            </a>
                            <button type="button"
                                onclick="openDeleteModal({{ $course->id }}, '{{ addslashes($course->title) }}')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-trash mr-1"></i>ลบ
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                        @if ($search)
                            <div
                                class="w-20 h-20 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-search text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ไม่พบรายวิชาที่ค้นหา</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">ลองค้นหาด้วยคำอื่น</p>
                            <a href="{{ route('teacher.courses.index') }}"
                                class="inline-flex items-center px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>ดูรายวิชาทั้งหมด
                            </a>
                        @else
                            <div
                                class="w-20 h-20 mx-auto mb-4 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-book-open text-4xl text-indigo-500"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีรายวิชา</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">เริ่มต้นสร้างรายวิชาแรกของคุณ</p>
                            <a href="{{ route('teacher.courses.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                <i class="fas fa-plus mr-2"></i>สร้างรายวิชาใหม่
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($courses->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md px-4 py-3">
                    {{ $courses->withQueryString()->links() }}
                </div>
            </div>
        @endif

        <!-- Stats Summary -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $courses->total() }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">รายวิชาทั้งหมด</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center">
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $courses->sum('enrollments_count') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">นักเรียนทั้งหมด</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center">
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $courses->sum('modules_count') }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">โมดูลทั้งหมด</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 text-center">
                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $courses->sum(fn($c) => $c->modules->sum(fn($m) => $m->lessons->count())) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">บทเรียนทั้งหมด</p>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
                onclick="closeDeleteModal()"></div>

            <!-- Modal panel -->
            <div
                class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="p-6">
                    <!-- Warning Icon -->
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-600 dark:text-red-400"></i>
                    </div>

                    <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-2">
                        ยืนยันการลบรายวิชา
                    </h3>

                    <p class="text-center text-gray-600 dark:text-gray-400 mb-2">
                        คุณกำลังจะลบรายวิชา:
                    </p>
                    <p class="text-center text-lg font-semibold text-red-600 dark:text-red-400 mb-4"
                        id="deleteCourseTitle"></p>

                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-700 dark:text-red-300">
                            <i class="fas fa-warning mr-1"></i>
                            <strong>คำเตือน:</strong> การลบรายวิชาจะลบข้อมูลทั้งหมดรวมถึง:
                        </p>
                        <ul class="text-sm text-red-600 dark:text-red-400 mt-2 ml-5 list-disc">
                            <li>โมดูลและบทเรียนทั้งหมด</li>
                            <li>แบบทดสอบและคำถามทั้งหมด</li>
                            <li>ข้อมูลการลงทะเบียนของนักเรียน</li>
                            <li>ข้อมูลความคืบหน้าการเรียน</li>
                        </ul>
                    </div>

                    <p class="text-center text-gray-700 dark:text-gray-300 mb-2">
                        พิมพ์ <span
                            class="font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2 py-0.5 rounded">DELETE</span>
                        เพื่อยืนยัน:
                    </p>

                    <input type="text" id="deleteConfirmInput"
                        class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-center text-lg font-mono tracking-widest focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                        placeholder="พิมพ์ DELETE ที่นี่" autocomplete="off" oninput="checkDeleteInput()">

                    <p id="deleteInputError" class="text-center text-red-500 text-sm mt-2 hidden">
                        <i class="fas fa-times-circle mr-1"></i>กรุณาพิมพ์ DELETE ให้ถูกต้อง
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                        <i class="fas fa-times mr-1"></i>ยกเลิก
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteBtn" disabled
                            class="w-full px-4 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-red-600">
                            <i class="fas fa-trash mr-1"></i>ลบรายวิชา
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCourseId = null;

        function openDeleteModal(courseId, courseTitle) {
            currentCourseId = courseId;
            document.getElementById('deleteCourseTitle').textContent = '"' + courseTitle + '"';
            document.getElementById('deleteForm').action = '{{ url('teacher/courses') }}/' + courseId;
            document.getElementById('deleteConfirmInput').value = '';
            document.getElementById('confirmDeleteBtn').disabled = true;
            document.getElementById('deleteInputError').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteConfirmInput').focus();
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteConfirmInput').value = '';
            currentCourseId = null;
        }

        function checkDeleteInput() {
            const input = document.getElementById('deleteConfirmInput').value;
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const errorMsg = document.getElementById('deleteInputError');

            if (input === 'DELETE') {
                confirmBtn.disabled = false;
                errorMsg.classList.add('hidden');
            } else {
                confirmBtn.disabled = true;
                if (input.length > 0 && input !== 'DELETE') {
                    errorMsg.classList.remove('hidden');
                } else {
                    errorMsg.classList.add('hidden');
                }
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
