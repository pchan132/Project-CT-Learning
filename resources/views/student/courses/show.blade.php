@extends('layouts.app')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $course->title }}
        </h2>
        <a href="{{ route('student.courses.index') }}"
            class=" hover:text-blue-500 text-sm flex items-center border bg-blue-600 text-white rounded px-2 py-1 hover:bg-blue-100 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            กลับไปหน้ารายวิชา
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Course Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden mb-8">
            <!-- Course Cover with Gradient Overlay -->
            <div class="relative h-48 md:h-64">
                @if ($course->cover_image_url)
                    <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                @endif

                <!-- Course Title Overlay -->
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <h1 class="text-2xl md:text-3xl font-bold mb-2 drop-shadow-lg">{{ $course->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-white/90">
                        <div class="flex items-center">
                            @if ($course->teacher->profile_image)
                                <img src="{{ asset('storage/' . $course->teacher->profile_image) }}"
                                    alt="{{ $course->teacher->name }}"
                                    class="w-8 h-8 rounded-full mr-2 border-2 border-white/50 object-cover">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full mr-2 bg-white/20 flex items-center justify-center text-sm font-bold">
                                    {{ mb_substr($course->teacher->name, 0, 1) }}
                                </div>
                            @endif
                            <span>ผู้สอน: {{ $course->teacher->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Main Progress Bar -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">ความคืบหน้าการเรียน</span>
                            <span
                                class="text-2xl font-bold {{ $progress >= 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $progress }}%</span>
                        </div>
                        <div class="relative w-full h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="absolute top-0 left-0 h-full rounded-full transition-all duration-700 ease-out
                                        {{ $progress >= 100 ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-blue-400 to-purple-500' }}"
                                style="width: {{ $progress }}%">
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>{{ count($completedLessons) }} / {{ $course->total_lessons }} บทเรียน</span>
                            @if ($progress >= 100)
                                <span class="text-green-600 font-medium">🎉 เรียนครบทุกบทแล้ว!</span>
                            @else
                                <span>เหลืออีก {{ $course->total_lessons - count($completedLessons) }} บทเรียน</span>
                            @endif
                        </div>
                    </div>

                    <!-- Unenroll Button -->
                    <form action="{{ route('student.courses.unenroll', $course) }}" method="POST" class="shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('คุณแน่ใจหรือไม่ที่จะถอนการลงทะเบียนคอร์สนี้? ข้อมูลการเรียนทั้งหมดจะถูกลบ')"
                            class="text-red-600 hover:text-red-800 text-sm flex items-center px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            ถอนการลงทะเบียน
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div
                class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0 divide-gray-200 dark:divide-gray-700">
                <div class="p-4 text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $course->total_modules }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">โมดูล</div>
                </div>
                <div class="p-4 text-center">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $course->total_lessons }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">บทเรียน</div>
                </div>
                <div class="p-4 text-center">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ count($completedLessons) }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">เรียนเสร็จแล้ว</div>
                </div>
                <div class="p-4 text-center">
                    @php
                        $totalQuizzes = 0;
                        $passedQuizzes = 0;
                        foreach ($course->modules as $module) {
                            $totalQuizzes += $module->quizzes->count();
                            foreach ($module->quizzes as $quiz) {
                                if ($quiz->hasPassedByStudent(auth()->id())) {
                                    $passedQuizzes++;
                                }
                            }
                        }
                    @endphp
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                        {{ $passedQuizzes }}/{{ $totalQuizzes }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">แบบทดสอบผ่าน</div>
                </div>
            </div>

            <!-- Certificate Section -->
            @php
                $existingCertificate = App\Models\Certificate::where('student_id', auth()->id())
                    ->where('course_id', $course->id)
                    ->first();

                $canGetCertificate = $progress >= 100;
                if ($canGetCertificate && $totalQuizzes > 0) {
                    $canGetCertificate = $passedQuizzes == $totalQuizzes;
                }
            @endphp

            @if ($existingCertificate)
                <div
                    class="p-6 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-t-4 border-yellow-400">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div
                                class="w-14 h-14 bg-yellow-100 dark:bg-yellow-800 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">🎉 คุณได้รับใบประกาศนียบัตรแล้ว!
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">เลขที่:
                                    {{ $existingCertificate->certificate_number }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('student.certificates.show', $existingCertificate->id) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                ดูและพิมพ์ใบประกาศนียบัตร
                            </a>
                            {{-- <a href="{{ route('student.certificates.download', $existingCertificate->id) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                ดาวน์โหลด
                            </a> --}}
                        </div>
                    </div>
                </div>
            @elseif ($canGetCertificate)
                <div
                    class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-t-4 border-green-400">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div
                                class="w-14 h-14 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">🎊 ยินดีด้วย!
                                    คุณเรียนจบคอร์สนี้แล้ว</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">คลิกปุ่มเพื่อขอรับใบประกาศนียบัตร</p>
                            </div>
                        </div>
                        <form action="{{ route('student.certificates.generate', $course) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg hover:from-yellow-500 hover:to-yellow-600 font-bold shadow-lg flex items-center transition-all transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                ขอใบประกาศนียบัตร
                            </button>
                        </form>
                    </div>
                </div>
            @elseif ($progress >= 100)
                <div
                    class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-t-4 border-blue-400">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">เกือบจะได้ใบประกาศนียบัตรแล้ว!</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">คุณต้องทำแบบทดสอบให้ผ่านทุกบท
                                ({{ $passedQuizzes }}/{{ $totalQuizzes }} ผ่านแล้ว)</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content - Modules and Lessons -->
            <div class="lg:col-span-3 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    เนื้อหารายวิชา
                </h2>

                @if ($course->modules->count() > 0)
                    @foreach ($course->modules->sortBy('order') as $moduleIndex => $module)
                        @php
                            $moduleLessons = $module->lessons->sortBy('order');
                            $moduleCompletedCount = $moduleLessons->whereIn('id', $completedLessons)->count();
                            $moduleProgress =
                                $moduleLessons->count() > 0
                                    ? round(($moduleCompletedCount / $moduleLessons->count()) * 100)
                                    : 0;
                        @endphp
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden module-card">
                            <!-- Module Header -->
                            <button onclick="toggleModule({{ $moduleIndex }})"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full {{ $moduleProgress >= 100 ? 'bg-green-100 dark:bg-green-800' : 'bg-blue-100 dark:bg-blue-800' }} flex items-center justify-center mr-4">
                                        @if ($moduleProgress >= 100)
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <span
                                                class="text-blue-600 dark:text-blue-400 font-bold">{{ $module->order }}</span>
                                        @endif
                                    </div>
                                    <div class="text-left">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $module->title }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $moduleLessons->count() }}
                                            บทเรียน • {{ $moduleCompletedCount }} เสร็จแล้ว</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <!-- Module Mini Progress -->
                                    <div class="hidden sm:flex items-center gap-2">
                                        <div class="w-24 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                            <div class="h-full {{ $moduleProgress >= 100 ? 'bg-green-500' : 'bg-blue-500' }} rounded-full transition-all"
                                                style="width: {{ $moduleProgress }}%"></div>
                                        </div>
                                        <span
                                            class="text-sm font-medium {{ $moduleProgress >= 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $moduleProgress }}%</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 module-arrow-{{ $moduleIndex }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>

                            <!-- Module Content -->
                            <div id="module-content-{{ $moduleIndex }}" class="module-content">
                                @if ($module->description)
                                    <div
                                        class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-600">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->description }}</p>
                                    </div>
                                @endif

                                <!-- Lessons -->
                                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($moduleLessons as $lesson)
                                        @php $isLessonCompleted = in_array($lesson->id, $completedLessons); @endphp
                                        <a href="{{ route('student.courses.learn-lesson', [$course, $lesson]) }}"
                                            class="flex items-center px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                            <!-- Status Icon -->
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center mr-4 shrink-0
                                                        {{ $isLessonCompleted ? 'bg-green-100 dark:bg-green-800' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-blue-100 dark:group-hover:bg-blue-800' }}">
                                                @if ($isLessonCompleted)
                                                    <svg class="w-5 h-5 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            </div>

                                            <!-- Lesson Info -->
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate">
                                                    {{ $lesson->title }}
                                                </h4>
                                                <div
                                                    class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1 space-x-3">
                                                    <span class="flex items-center">
                                                        @switch($lesson->content_type)
                                                            @case('VIDEO')
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            @break

                                                            @case('PDF')
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            @break

                                                            @default
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                    </path>
                                                                </svg>
                                                        @endswitch
                                                        {{ $lesson->content_type_label }}
                                                    </span>
                                                    <span>บทที่ {{ $lesson->order }}</span>
                                                </div>
                                            </div>

                                            <!-- Action -->
                                            <div class="ml-4 shrink-0">
                                                <span
                                                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                                                            {{ $isLessonCompleted
                                                                ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300'
                                                                : 'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-300 group-hover:bg-blue-200' }}">
                                                    {{ $isLessonCompleted ? 'เรียนซ้ำ' : 'เริ่มเรียน' }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>

                                <!-- Quizzes -->
                                @if ($module->quizzes->count() > 0)
                                    <div
                                        class="bg-orange-50 dark:bg-orange-900/20 border-t-2 border-orange-200 dark:border-orange-700">
                                        <div class="px-6 py-3 border-b border-orange-200 dark:border-orange-700">
                                            <h4
                                                class="text-sm font-semibold text-orange-800 dark:text-orange-300 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                แบบทดสอบ ({{ $module->quizzes->count() }} ข้อสอบ)
                                            </h4>
                                        </div>
                                        <div class="divide-y divide-orange-200 dark:divide-orange-700">
                                            @foreach ($module->quizzes as $quiz)
                                                @php
                                                    $hasPassed = $quiz->hasPassedByStudent(auth()->id());
                                                    $bestAttempt = $quiz->getBestAttemptForStudent(auth()->id());
                                                @endphp
                                                <a href="{{ route('student.courses.modules.quizzes.show', [$course, $module, $quiz]) }}"
                                                    class="flex items-center px-6 py-4 hover:bg-orange-100 dark:hover:bg-orange-800/30 transition-colors group">
                                                    <!-- Quiz Status -->
                                                    <div
                                                        class="w-10 h-10 rounded-full flex items-center justify-center mr-4 shrink-0
                                                                {{ $hasPassed ? 'bg-green-100 dark:bg-green-800' : 'bg-orange-100 dark:bg-orange-800' }}">
                                                        @if ($hasPassed)
                                                            <svg class="w-5 h-5 text-green-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-orange-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <!-- Quiz Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <h4 class="font-medium text-gray-900 dark:text-white truncate">
                                                                {{ $quiz->title }}</h4>
                                                            @if ($hasPassed)
                                                                <span
                                                                    class="shrink-0 px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">ผ่านแล้ว</span>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="flex items-center text-sm text-gray-600 dark:text-gray-400 mt-1 space-x-3">
                                                            <span>{{ $quiz->questions->count() }} ข้อ</span>
                                                            <span>• ผ่าน {{ $quiz->passing_score }}%</span>
                                                            @if ($bestAttempt)
                                                                <span>• คะแนนสูงสุด: <strong
                                                                        class="{{ $bestAttempt->passed ? 'text-green-600' : 'text-red-600' }}">{{ $bestAttempt->score }}%</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Action -->
                                                    <div class="ml-4 shrink-0">
                                                        <span
                                                            class="px-3 py-1.5 rounded-lg text-sm font-medium
                                                                    {{ $hasPassed ? 'bg-green-100 text-green-700' : 'bg-orange-600 text-white group-hover:bg-orange-700' }}">
                                                            {{ $hasPassed ? 'ดูผลลัพธ์' : 'ทำแบบทดสอบ' }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">ยังไม่มีเนื้อหาในคอร์สนี้</h3>
                        <p class="text-gray-600 dark:text-gray-400">ผู้สอนกำลังเพิ่มเนื้อหาในคอร์สนี้</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Continue Learning Card -->
                @if ($course->lessons->count() > 0)
                    @php
                        $nextLesson = $course
                            ->lessons()
                            ->whereNotIn('lessons.id', $completedLessons)
                            ->orderBy('lessons.order')
                            ->first();
                    @endphp

                    @if ($nextLesson)
                        <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-semibold">บทเรียนถัดไป</h3>
                            </div>
                            <h4 class="font-medium mb-2 line-clamp-2">{{ $nextLesson->title }}</h4>
                            <p class="text-sm text-white/80 mb-4">{{ $nextLesson->content_type_label }}</p>
                            <a href="{{ route('student.courses.learn-lesson', [$course, $nextLesson]) }}"
                                class="block w-full bg-white text-blue-600 text-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                                เรียนต่อ →
                            </a>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="text-center">
                                <div class="text-4xl mb-3">🎉</div>
                                <h3 class="text-lg font-bold mb-2">ยินดีด้วย!</h3>
                                <p class="text-sm text-white/90">คุณเรียนครบทุกบทเรียนแล้ว</p>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Course Info Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        ข้อมูลรายวิชา
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $course->description }}</p>
                </div>

                <!-- Teacher Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        ผู้สอน
                    </h3>
                    <div class="flex items-center">
                        <div
                            class="w-14 h-14 rounded-full overflow-hidden bg-gradient-to-br from-blue-400 to-purple-500 flex-shrink-0">
                            @if ($course->teacher->profile_image)
                                <img src="{{ asset('storage/' . $course->teacher->profile_image) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-xl font-bold">
                                    {{ strtoupper(substr($course->teacher->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $course->teacher->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $course->teacher->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModule(index) {
            const content = document.getElementById('module-content-' + index);
            const arrow = document.querySelector('.module-arrow-' + index);

            if (content.style.display === 'none') {
                content.style.display = 'block';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                arrow.style.transform = 'rotate(-90deg)';
            }
        }
    </script>
@endsection
