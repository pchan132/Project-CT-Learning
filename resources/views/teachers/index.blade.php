@extends('layouts.app')

@section('title', 'อาจารย์ทั้งหมด')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-3"></i>
                อาจารย์ผู้สอน
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-lg">
                พบกับอาจารย์ผู้เชี่ยวชาญที่พร้อมถ่ายทอดความรู้ให้คุณ
            </p>
        </div>

        <!-- Teachers Grid -->
        @if ($teachers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($teachers as $teacher)
                    <a href="{{ route('teachers.show', $teacher) }}"
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Profile Image -->
                        <div
                            class="relative aspect-square bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 overflow-hidden">
                            @if ($teacher->profile_image_url)
                                <img src="{{ $teacher->profile_image_url }}" alt="{{ $teacher->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div
                                        class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                                        <span class="text-4xl font-bold text-white">{{ $teacher->initials }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Course Count Badge -->
                            <div
                                class="absolute top-3 right-3 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                {{ $teacher->teaching_courses_count }} คอร์ส
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-5 text-center">
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $teacher->name }}
                            </h3>
                            @if ($teacher->position)
                                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mt-1">
                                    {{ $teacher->position }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-envelope mr-1"></i>
                                {{ $teacher->email }}
                            </p>

                            <!-- View Profile Button -->
                            <div class="mt-4">
                                <span
                                    class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 font-medium group-hover:underline">
                                    ดูโปรไฟล์และคอร์ส
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div
                    class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-user-slash text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีอาจารย์</h3>
                <p class="text-gray-500 dark:text-gray-400">ยังไม่มีอาจารย์ในระบบขณะนี้</p>
            </div>
        @endif
    </div>
@endsection
