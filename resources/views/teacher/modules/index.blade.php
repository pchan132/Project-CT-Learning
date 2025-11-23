<x-app-layout>
    <!-- Custom CSS for the Clean, Structured Theme -->
    <style>
        /* Define custom colors */
        :root {
            --header-bg: #0E2A5D;
            /* Deep Navy Blue */
            --accent-blue: #1D4ED8;
            /* Tailwind blue-700 */
        }

        .futuristic-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            /* Subtle gray border */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            /* Soft shadow */
            transition: all 0.3s ease-in-out;
        }

        .futuristic-card:hover {
            box-shadow: 0 10px 15px -3px rgba(30, 64, 175, 0.1), 0 4px 6px -4px rgba(30, 64, 175, 0.05);
            transform: translateY(-2px);
            /* Slight lift */
        }

        /* Floating/Slide-in Animation for sequential elements */
        @keyframes slide-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slide-in-up 0.5s ease-out forwards;
            opacity: 0;
        }

        /* Icon for drag handle */
        .drag-handle {
            color: #9ca3af;
            cursor: grab;
        }
    </style>

    <!-- Header Section: Deep Blue Bar (Matching Image) -->
    <div class="bg-[var(--header-bg)] text-white pt-4 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Top Nav/Status Bar -->
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('teacher.courses.show', $course) }}"
                    class="text-blue-300 hover:text-white text-sm font-light">
                    <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าหลักของคอร์ส
                </a>
                <!-- Static Stats Mockup (Assuming these properties are available on $course) -->
                <div class="text-sm font-light bg-blue-800 px-3 py-1 rounded-full">
                    {{ $course->total_modules ?? 'X' }} โมดูล · {{ $course->total_lessons ?? 'Y' }} บทเรียน
                </div>
            </div>
            <!-- Main Title -->
            <h1 class="text-3xl font-bold">จัดการเนื้อหาวิชา</h1>
            <p class="text-blue-200 mt-1">{{ $course->title }}</p>
        </div>
    </div>

    <!-- Main Content Area: Overlaps Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">

        <!-- Module Header and Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">โมดูลและบทเรียน</h2>
            <a href="{{ route('teacher.courses.modules.create', $course) }}"
                class="bg-[var(--accent-blue)] hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded-lg shadow-md flex items-center">
                <i class="fas fa-plus mr-2"></i>เพิ่มโมดูล
            </a>
        </div>

        <!-- Success/Error Messages (Styled to match the white theme) -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg relative mb-6"
                role="alert">
                <span class="block sm:inline font-medium"><i class="fas fa-check-circle mr-2"></i></span>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline font-medium"><i class="fas fa-times-circle mr-2"></i></span>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Modules List -->
        <div>
            @if ($modules->count() > 0)
                <ul class="space-y-4">
                    @foreach ($modules as $module)
                        <!-- Module Card with Staggered Animation -->
                        <li class="futuristic-card rounded-xl animate-slide-in"
                            style="animation-delay: {{ $loop->index * 0.1 }}s;">

                            <!-- Module Header Row -->
                            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Drag Handle Placeholder -->
                                    <i class="fas fa-grip-vertical drag-handle text-lg"></i>

                                    <!-- Module Info -->
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            โมดูล {{ $module->order }}: {{ $module->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $module->description ?? 'คำอธิบาย Module' }}</p>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $module->total_lessons }} บทเรียน
                                        </span>
                                    </div>
                                </div>

                                <!-- Module Actions -->
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <!-- Expand/Collapse Placeholder -->
                                    <button class="p-2 hover:bg-gray-100 rounded-full transition"><i
                                            class="fas fa-chevron-up"></i></button>

                                    <!-- Edit Module -->
                                    <a href="{{ route('teacher.courses.modules.edit', [$course, $module]) }}"
                                        class="p-2 hover:bg-gray-100 rounded-full transition hover:text-blue-600">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Module -->
                                    <form action="{{ route('teacher.courses.modules.destroy', [$course, $module]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 hover:bg-red-50 rounded-full transition hover:text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Nested Lesson List Container -->
                            <div class="divide-y divide-gray-100">
                                {{-- NOTE: Assuming $module->lessons exists. Below is a mock structure for the visual. --}}
                                @if ($module->lessons->count() > 0)
                                    @foreach ($module->lessons as $lesson)
                                        <div class="pl-12 pr-6 py-3 flex items-center justify-between hover:bg-gray-50">
                                            <div class="flex items-center space-x-4 flex-1">
                                                <!-- Drag Handle & Lesson Order -->
                                                <i class="fas fa-grip-lines drag-handle text-sm"></i>

                                                <!-- Lesson Title & Info -->
                                                <div>
                                                    <span class="text-gray-700 font-medium">
                                                        {{ $lesson->order }}. {{ $lesson->title }}
                                                        <span
                                                            class="text-sm text-gray-400">({{ $lesson->duration ?? '10 นาที' }})</span>
                                                    </span>
                                                    <p class="text-xs text-gray-500">ประเภท:
                                                        {{ $lesson->content_type ?? 'วิดีโอ' }}</p>
                                                </div>
                                            </div>

                                            <!-- Lesson Actions -->
                                            <div class="flex items-center space-x-1 text-gray-500">
                                                <!-- Edit Lesson -->
                                                <a href="{{ route('teacher.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
                                                    class="p-2 hover:bg-gray-100 rounded-full transition hover:text-blue-600">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>

                                                <!-- Delete Lesson -->
                                                <form
                                                    action="{{ route('teacher.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}"
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 hover:bg-red-50 rounded-full transition hover:text-red-600">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <!-- Add Lesson Button -->
                                <div class="px-6 py-4">
                                    <a href="{{ route('teacher.courses.modules.lessons.create', [$course, $module]) }}"
                                        class="text-blue-600 hover:text-blue-800 w-full py-2 flex items-center justify-center border-2 border-dashed border-blue-200 hover:border-blue-400 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>เพิ่มบทเรียน
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <!-- No Modules Found -->
                <div class="text-center py-16 futuristic-card rounded-xl">
                    <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">ยังไม่มี Modules</h3>
                    <p class="text-gray-600 mb-6">เริ่มต้นการสร้าง Module แรกสำหรับคอร์สนี้</p>
                    <a href="{{ route('teacher.courses.modules.create', $course) }}"
                        class="bg-[var(--accent-blue)] hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-xl transition transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>สร้าง Module แรก
                    </a>
                </div>
            @endif
        </div>

        <!-- Bottom Back Button (Removed from original file, but good practice to keep the navigation clean) -->
        <div class="mt-10 pt-6 border-t border-gray-200">
            <a href="{{ route('teacher.courses.show', $course) }}"
                class="text-gray-500 hover:text-blue-600 font-medium transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปที่รายละเอียดคอร์ส
            </a>
        </div>
    </div>
</x-app-layout>