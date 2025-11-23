<x-app-layout>
    <!-- Custom CSS for the Futuristic Light Theme -->
    <style>
        /* Define custom neon color constants for easy use */
        :root {
            --neon-cyan: #06b6d4;
            /* Tailwind cyan-500 */
        }

        .futuristic-card {
            /* Clean White Background with soft, luminous shadow */
            background-color: white;
            border: 1px solid #e0f7fa;
            /* Light cyan border for definition */
            /* Initial subtle glow shadow */
            box-shadow: 0 10px 30px rgba(0, 188, 212, 0.05);
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .futuristic-card:hover {
            /* Stronger neon glow and pronounced lift on hover */
            box-shadow: 0 10px 20px -3px rgba(6, 182, 212, 0.4), 0 5px 10px -2px rgba(6, 182, 212, 0.2);
            transform: translateY(-4px);
            /* More pronounced lift */
        }

        .text-neon-cyan {
            color: var(--neon-cyan);
        }

        .bg-neon-cyan {
            background-color: var(--neon-cyan);
        }

        /* Soft pulse animation for data activity indicator */
        @keyframes soft-pulse {

            0%,
            100% {
                opacity: 1;
                text-shadow: 0 0 5px var(--neon-cyan);
            }

            50% {
                opacity: 0.7;
                text-shadow: 0 0 15px var(--neon-cyan);
            }
        }

        .animate-soft-pulse {
            animation: soft-pulse 2s infinite ease-in-out;
        }
    </style>

    <!-- Main Container: Light Mode Base -->
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 bg-gray-50 min-h-screen text-gray-900">

        <!-- Header Section: High-Tech Look -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-wider">
                        {{ $course->title }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        <a href="{{ route('teacher.courses.index') }}"
                            class="text-cyan-600 hover:text-cyan-800 transition duration-300">คอร์สของฉัน</a>
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="font-medium text-gray-700">รายละเอียดคอร์ส</span>
                    </p>
                </div>
                <div class="flex space-x-4">
                    <!-- Edit Course Button: High-Contrast Secondary Action (Animated) -->
                    <a href="{{ route('teacher.courses.edit', $course) }}"
                        class="bg-white border-2 border-cyan-500 text-cyan-600 hover:bg-cyan-50 font-semibold py-3 px-6 rounded-xl transition duration-300 ease-in-out shadow-lg shadow-cyan-500/10 hover:shadow-xl hover:shadow-cyan-500/30 transform hover:scale-[1.02]">
                        <i class="fas fa-edit mr-2"></i>แก้ไขคอร์ส
                    </a>
                    <!-- Add Module Button: Primary Neon Action (Animated) -->
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-neon-cyan hover:bg-cyan-400 text-white font-bold py-3 px-6 rounded-xl transition duration-300 ease-in-out shadow-xl shadow-cyan-500/50 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Info Card: Elevated Futuristic Card -->
        <div class="futuristic-card rounded-2xl mb-8 shadow-2xl hover:shadow-cyan-500/40">
            <div class="px-6 py-8 sm:p-10">
                <div class="flex items-start">
                    <!-- Course Image/Placeholder -->
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="{{ $course->title }}"
                            class="h-32 w-32 rounded-xl object-cover mr-10 shadow-lg border-2 border-cyan-300">
                    @else
                        <div
                            class="h-32 w-32 bg-gray-100 rounded-xl flex items-center justify-center mr-10 border-2 border-cyan-300 shadow-inner">
                            <!-- Animated Placeholder Icon -->
                            <i class="fas fa-book text-neon-cyan text-5xl animate-soft-pulse"></i>
                        </div>
                    @endif

                    <!-- Details and Stats -->
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">{{ $course->description }}</p>

                        <!-- Core Stats Row: Data Visuals -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 border-t pt-4 border-gray-200">
                            <div class="text-left">
                                <div class="text-4xl font-extrabold text-cyan-600">{{ $course->total_modules }}</div>
                                <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">Modules (Units)</div>
                            </div>
                            <div class="text-left">
                                <div class="text-4xl font-extrabold text-blue-600">{{ $course->total_lessons }}</div>
                                <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">บทเรียน (Files)</div>
                            </div>
                            <div class="text-left">
                                <div class="text-4xl font-extrabold text-purple-600">
                                    {{ $course->enrollments->count() }}</div>
                                <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">นักเรียน (Users)</div>
                            </div>
                            <div class="text-left">
                                <div class="text-3xl font-extrabold text-gray-600">
                                    {{ $course->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500 uppercase tracking-widest mt-1">สร้างเมื่อ (Log)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Section: Clean Digital List -->
        <div class="futuristic-card rounded-2xl shadow-2xl">
            <div class="px-6 py-6 sm:px-8 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Modules ในคอร์ส</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-600">
                            จัดการ Modules และโครงสร้างเนื้อหาทั้งหมดในคอร์สนี้
                        </p>
                    </div>
                    <!-- Secondary Add Module Button -->
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="inline-flex items-center px-4 py-2 border border-cyan-500 text-sm leading-4 font-medium rounded-xl text-cyan-600 bg-cyan-50 hover:bg-cyan-100/50 focus:outline-none transition duration-150 transform hover:translate-x-1">
                        <i class="fas fa-plus mr-2"></i>เพิ่ม Module
                    </a>
                </div>
            </div>

            @if ($course->modules->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach ($course->modules as $module)
                        <div class="px-6 py-6 transition duration-300 ease-in-out hover:bg-cyan-50/50 hover:shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <!-- Module Order Circle (Accent) -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-12 w-12 rounded-full bg-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 border border-cyan-400">
                                            <span class="text-white text-lg font-bold">{{ $module->order }}</span>
                                        </div>
                                    </div>

                                    <!-- Module Title and Info -->
                                    <div class="ml-5">
                                        <div class="flex items-center">
                                            <h4 class="text-xl font-semibold text-gray-900">{{ $module->title }}</h4>
                                            <span
                                                class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-300">
                                                {{ $module->total_lessons }} บทเรียน
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Module ID: {{ $module->order }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Module Actions: Subtle hover for controls (Animated) -->
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-cyan-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                        <i class="fas fa-eye mr-1"></i>ดู (View)
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-green-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                        <i class="fas fa-list mr-1"></i>บทเรียน (Lessons)
                                    </a>
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="text-gray-500 hover:text-purple-600 transition duration-150 text-sm font-medium p-2 rounded-lg transform hover:scale-110">
                                        <i class="fas fa-edit mr-1"></i>แก้ไข (Edit)
                                    </a>
                                </div>
                            </div>

                            <!-- Show first few lessons in this module -->
                            @if ($module->lessons->count() > 0)
                                <div class="mt-5 ml-17 pl-1">
                                    <!-- Neon Line Indicator -->
                                    <div
                                        class="text-sm font-semibold text-cyan-600 mb-2 border-l-4 pl-4 border-cyan-400 tracking-wider">
                                        // LOG: Latest Lessons Accessed
                                    </div>
                                    <div class="space-y-2">
                                        @foreach ($module->lessons->take(3) as $lesson)
                                            <div
                                                class="flex items-center text-sm text-gray-700 transition duration-200 hover:text-gray-900 hover:bg-cyan-50 p-1 rounded-md">
                                                <div
                                                    class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center mr-3 border border-gray-300">
                                                    <span
                                                        class="text-gray-500 text-xs font-semibold">{{ $lesson->order }}</span>
                                                </div>
                                                <span class="font-medium truncate">{{ $lesson->title }}</span>
                                                <span
                                                    class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                    @if ($lesson->content_type === 'PDF') bg-red-100 text-red-700 border border-red-300
                                                    @elseif($lesson->content_type === 'VIDEO') bg-purple-100 text-purple-700 border border-purple-300
                                                    @elseif($lesson->content_type === 'TEXT') bg-gray-100 text-gray-700 border border-gray-300 @endif">
                                                    {{ $lesson->content_type }}
                                                </span>
                                            </div>
                                        @endforeach

                                        @if ($module->lessons->count() > 3)
                                            <div class="text-sm text-gray-500 pt-2 border-t border-gray-200 mt-2">
                                                <a href="{{ route('teacher.courses.modules.lessons.index', [$course, $module]) }}"
                                                    class="hover:text-cyan-600 font-medium transition duration-150">
                                                    ... ดูบทเรียนทั้งหมดอีก {{ $module->lessons->count() - 3 }} บทเรียน
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">ACCESS PENDING: No Modules Found</h3>
                    <p class="text-gray-600 mb-6">เริ่มต้นการสร้าง Module แรกเพื่อเปิดใช้งานคอร์ส</p>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-neon-cyan hover:bg-cyan-400 text-white font-bold py-3 px-6 rounded-xl shadow-xl shadow-cyan-500/50 transition duration-300 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>สร้าง Module แรก (INIT)
                    </a>
                </div>
            @endif
        </div>

        <!-- Detailed Statistics: Data Panels -->
        @if ($course->total_lessons > 0)
            <div class="mt-10">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-cyan-400 pb-2">SYSTEM STATUS:
                    สรุปสถิติเนื้อหาคอร์ส</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Stat Card 1 (Modules) -->
                    <div
                        class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                        <div class="px-5 py-6">
                            <div class="text-center">
                                <i class="fas fa-cubes text-cyan-500 text-3xl mb-2"></i>
                                <div class="text-4xl font-extrabold text-cyan-600">{{ $course->total_modules }}</div>
                                <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Modules ทั้งหมด</div>
                            </div>
                        </div>
                    </div>
                    <!-- Stat Card 2 (Lessons) -->
                    <div
                        class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                        <div class="px-5 py-6">
                            <div class="text-center">
                                <i class="fas fa-file-alt text-blue-500 text-3xl mb-2"></i>
                                <div class="text-4xl font-extrabold text-blue-600">{{ $course->total_lessons }}</div>
                                <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">บทเรียนทั้งหมด</div>
                            </div>
                        </div>
                    </div>
                    <!-- Stat Card 3 (PDF) -->
                    <div
                        class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                        <div class="px-5 py-6">
                            <div class="text-center">
                                <i class="fas fa-file-pdf text-red-500 text-3xl mb-2"></i>
                                <div class="text-4xl font-extrabold text-red-600">
                                    {{ $course->lessons()->where('content_type', 'PDF')->count() }}</div>
                                <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Data Type: PDF</div>
                            </div>
                        </div>
                    </div>
                    <!-- Stat Card 4 (Video) -->
                    <div
                        class="futuristic-card rounded-xl border border-cyan-300/50 transition duration-300 hover:border-cyan-500">
                        <div class="px-5 py-6">
                            <div class="text-center">
                                <i class="fas fa-video text-purple-500 text-3xl mb-2"></i>
                                <div class="text-4xl font-extrabold text-purple-600">
                                    {{ $course->lessons()->where('content_type', 'VIDEO')->count() }}</div>
                                <div class="text-sm text-gray-500 mt-1 uppercase tracking-widest">Data Type: VIDEO
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer / Back Button: Terminal Style -->
        <div class="mt-12 pt-6 border-t-2 border-gray-200">
            <a href="{{ route('teacher.courses.index') }}"
                class="text-gray-500 hover:text-cyan-600 font-medium transition duration-150 transform hover:scale-[1.02] inline-block">
                <i class="fas fa-arrow-left mr-2"></i>// Execute: RETURN_TO_DASHBOARD
            </a>
        </div>
    </div>
</x-app-layout>
