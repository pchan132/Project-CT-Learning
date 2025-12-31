@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ใบประกาศนียบัตรของฉัน
    </h2>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">ใบประกาศนียบัตรของฉัน</h1>
            <p class="text-gray-600 dark:text-gray-400">รวบรวมใบประกาศนียบัตรทั้งหมดที่คุณได้รับ</p>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($certificates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($certificates as $certificate)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border-2 border-yellow-400 dark:border-yellow-600">
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-4 text-center">
                            <i class="fas fa-award text-5xl text-white mb-2"></i>
                            <h3 class="text-white font-bold text-sm">ใบประกาศนียบัตร</h3>
                        </div>

                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                {{ $certificate->course->title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ $certificate->course->teacher->name }}</p>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                                    <span
                                        class="text-gray-700 dark:text-gray-300">{{ $certificate->certificate_number }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-calendar text-gray-500 mr-2"></i>
                                    <span
                                        class="text-gray-700 dark:text-gray-300">{{ $certificate->issued_date->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('student.certificates.show', $certificate->id) }}"
                                    class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm font-semibold">
                                    <i class="fas fa-eye mr-1"></i>ดูและพิมพ์ใบประกาศนียบัตร
                                </a>
                                {{-- <a href="{{ route('student.certificates.download', $certificate->id) }}"
                                    class="flex-1 text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-semibold">
                                    <i class="fas fa-download mr-1"></i>ดาวน์โหลด
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-award text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ยังไม่มีใบประกาศนียบัตร</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    เรียนรายวิชาให้จบและผ่านแบบทดสอบทุกบทเพื่อรับใบประกาศนียบัตร
                </p>
                <a href="{{ route('student.courses.my-courses') }}"
                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    <i class="fas fa-book mr-2"></i>ไปที่รายวิชาของฉัน
                </a>
            </div>
        @endif
    </div>
@endsection
