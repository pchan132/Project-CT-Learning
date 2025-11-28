@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        นักเรียนในคอร์ส
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <a href="{{ route('teacher.courses.show', $course) }}"
                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">นักเรียนในคอร์ส</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $course->title }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('teacher.courses.show', $course) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fas fa-book mr-2"></i>
                        ดูคอร์ส
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Students -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">นักเรียนทั้งหมด</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_students'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Progress -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ความคืบหน้าเฉลี่ย</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['avg_progress'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Completed Students -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-xl bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">เรียนจบแล้ว</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed_students'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Certificates Issued -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-xl bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                            <i class="fas fa-certificate text-yellow-600 dark:text-yellow-400 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ออกใบประกาศ</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['certificates_issued'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-list mr-2 text-blue-500"></i>
                        รายชื่อนักเรียน
                    </h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        ทั้งหมด {{ $studentsProgress->count() }} คน
                    </div>
                </div>
            </div>

            @if($studentsProgress->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    นักเรียน
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    ความคืบหน้า
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    บทเรียน
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    แบบทดสอบ
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    คะแนนเฉลี่ย
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    สถานะ
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    กิจกรรมล่าสุด
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($studentsProgress as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <!-- Student Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($data['student']->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $data['student']->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $data['student']->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Progress Bar -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-32 mx-auto">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-sm font-semibold 
                                                    @if($data['progress'] == 100) text-green-600 dark:text-green-400
                                                    @elseif($data['progress'] >= 50) text-blue-600 dark:text-blue-400
                                                    @else text-gray-600 dark:text-gray-400
                                                    @endif">
                                                    {{ $data['progress'] }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="h-2 rounded-full transition-all duration-300
                                                    @if($data['progress'] == 100) bg-green-500
                                                    @elseif($data['progress'] >= 50) bg-blue-500
                                                    @else bg-gray-400
                                                    @endif" 
                                                    style="width: {{ $data['progress'] }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Lessons -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                            <i class="fas fa-book-open mr-1"></i>
                                            {{ $data['completed_lessons'] }}/{{ $data['total_lessons'] }}
                                        </span>
                                    </td>

                                    <!-- Quizzes -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($data['total_quizzes'] > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                                @if($data['passed_quizzes'] == $data['total_quizzes']) 
                                                    bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @else 
                                                    bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300
                                                @endif">
                                                <i class="fas fa-clipboard-check mr-1"></i>
                                                {{ $data['passed_quizzes'] }}/{{ $data['total_quizzes'] }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>

                                    <!-- Average Score -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($data['avg_quiz_score'] !== null)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                @if($data['avg_quiz_score'] >= 80) 
                                                    bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                                @elseif($data['avg_quiz_score'] >= 60) 
                                                    bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300
                                                @else 
                                                    bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                                @endif">
                                                <i class="fas fa-star mr-1"></i>
                                                {{ $data['avg_quiz_score'] }}%
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($data['has_certificate'])
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                <i class="fas fa-certificate mr-1"></i>
                                                ได้ใบประกาศ
                                            </span>
                                        @elseif($data['progress'] == 100)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                เรียนจบแล้ว
                                            </span>
                                        @elseif($data['progress'] > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">
                                                <i class="fas fa-spinner mr-1"></i>
                                                กำลังเรียน
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-clock mr-1"></i>
                                                ยังไม่เริ่ม
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Last Activity -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($data['last_activity'])
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($data['last_activity'])->diffForHumans() }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ \Carbon\Carbon::parse($data['last_activity'])->format('d/m/Y H:i') }}
                                            </div>
                                        @else
                                            <div class="text-sm text-gray-400 dark:text-gray-500">
                                                ลงทะเบียนเมื่อ
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $data['enrolled_at']->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-gray-400 mr-2"></div>
                            <span>ยังไม่เริ่มเรียน</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                            <span>กำลังเรียน</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span>เรียนจบแล้ว</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-certificate text-yellow-500 mr-2"></i>
                            <span>ได้รับใบประกาศ</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="fas fa-users-slash text-gray-400 dark:text-gray-500 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">ยังไม่มีนักเรียนในคอร์สนี้</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">รอให้นักเรียนลงทะเบียนเรียนคอร์สนี้</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('teacher.courses.show', $course) }}"
                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าคอร์ส
            </a>
        </div>
    </div>
@endsection
