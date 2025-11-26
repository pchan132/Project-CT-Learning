@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ใบประกาศนียบัตร
    </h2>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <a href="{{ route('student.certificates.index') }}" class="hover:text-blue-600">ใบประกาศนียบัตรของฉัน</a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span>{{ $certificate->course->title }}</span>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Certificate Display -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden border-4 border-yellow-400 dark:border-yellow-600">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-400 p-8 text-center">
                <i class="fas fa-award text-7xl text-white mb-4"></i>
                <h1 class="text-4xl font-bold text-white mb-2">ใบประกาศนียบัตร</h1>
                <p class="text-white text-lg opacity-90">Certificate of Completion</p>
            </div>

            <!-- Body -->
            <div class="p-12 text-center">
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">ขอมอบใบประกาศนียบัตรนี้ให้แก่</p>
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">{{ $certificate->student->name }}</h2>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">สำเร็จการศึกษาจากคอร์ส</p>
                <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-8">{{ $certificate->course->title }}</h3>

                <p class="text-gray-600 dark:text-gray-400 mb-8">ผู้สอนโดย <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $certificate->course->teacher->name }}</span>
                </p>

                <!-- Details -->
                <div class="flex justify-center items-center gap-8 mb-8 text-sm">
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">เลขที่ใบประกาศนียบัตร</p>
                        <p class="font-mono font-bold text-gray-900 dark:text-white">{{ $certificate->certificate_number }}
                        </p>
                    </div>
                    <div class="w-px h-12 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400">วันที่ออก</p>
                        <p class="font-bold text-gray-900 dark:text-white">{{ $certificate->issued_date->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <!-- Seal/Badge -->
                <div class="flex justify-center mb-8">
                    <div
                        class="w-32 h-32 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-xl">
                        <i class="fas fa-certificate text-6xl text-white"></i>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-center gap-4">
                    <a href="{{ route('student.certificates.index') }}"
                        class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>กลับ
                    </a>
                    <a href="{{ route('student.certificates.download', $certificate->id) }}"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-lg">
                        <i class="fas fa-download mr-2"></i>ดาวน์โหลด PDF
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 text-center text-sm text-gray-600 dark:text-gray-400">
                <p>ออกให้โดย CT Learning Platform • {{ config('app.name') }}</p>
            </div>
        </div>

        <!-- Course Details -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>รายละเอียดคอร์ส
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-list text-2xl text-blue-500 mb-2"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400">โมดูลทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $certificate->course->modules->count() }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-book-open text-2xl text-green-500 mb-2"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400">บทเรียนทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $certificate->course->total_lessons }}
                    </p>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <i class="fas fa-clipboard-question text-2xl text-purple-500 mb-2"></i>
                    <p class="text-sm text-gray-600 dark:text-gray-400">แบบทดสอบทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $certificate->course->modules->sum(function ($module) {return $module->quizzes->count();}) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
