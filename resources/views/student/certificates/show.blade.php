@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ใบประกาศนียบัตร
    </h2>
@endsection

@php
    // ดึง template จาก certificate หรือใช้ template ที่ active
    $template = $certificate->template ?? \App\Models\CertificateTemplate::getActiveTemplate();

    // กำหนดสีเริ่มต้นถ้าไม่มี template
    $primaryColor = $template->primary_color ?? '#f59e0b';
    $borderColor = $template->border_color ?? '#d4af37';
    $textColor = $template->text_color ?? '#1f2937';

    // ครูผู้สอน
    $teacher = $certificate->course->teacher;
    $teacherSignature = $teacher->signature_image ?? null;
@endphp

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

        <!-- Certificate Display with Template Design -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden"
            style="border: 6px solid {{ $borderColor }};">

            {{-- Background Image --}}
            @if ($template && $template->background_image)
                <div class="relative"
                    style="background-image: url('{{ asset('storage/' . $template->background_image) }}'); background-size: cover; background-position: center;">
                @else
                    <div class="relative bg-gradient-to-br from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-900">
            @endif

            <!-- Header -->
            <div class="p-8 text-center"
                style="background: linear-gradient(135deg, {{ $primaryColor }}dd, {{ $primaryColor }}aa);">
                @if ($template && $template->logo_image)
                    <img src="{{ asset('storage/' . $template->logo_image) }}" alt="Logo"
                        class="w-20 h-20 mx-auto mb-4 object-contain">
                @else
                    <i class="fas fa-award text-7xl text-white mb-4"></i>
                @endif
                <h1 class="text-4xl font-bold text-white mb-2">ใบประกาศนียบัตร</h1>
                <p class="text-white text-lg opacity-90">Certificate of Completion</p>
            </div>

            <!-- Body -->
            <div class="p-12 text-center bg-white/90 dark:bg-gray-800/90">
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">ขอมอบใบประกาศนียบัตรนี้ให้แก่</p>
                <h2 class="text-4xl font-bold mb-6" style="color: {{ $textColor }};">{{ $certificate->student->name }}
                </h2>

                <div class="w-48 h-1 mx-auto mb-6"
                    style="background: linear-gradient(to right, transparent, {{ $primaryColor }}, transparent);"></div>

                <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">สำเร็จการศึกษาจากคอร์ส</p>
                <h3 class="text-3xl font-bold mb-8" style="color: {{ $primaryColor }};">{{ $certificate->course->title }}
                </h3>

                <!-- Signatures Section -->
                @if ($template)
                    <div class="grid grid-cols-2 gap-8 my-12 px-12">
                        @php
                            $adminPosition = $template->admin_signature_position ?? 'right';
                            $teacherPosition = $template->teacher_signature_position ?? 'left';
                        @endphp

                        {{-- Left Signature --}}
                        <div class="text-center">
                            @if ($teacherPosition === 'left' && $template->show_teacher_signature)
                                {{-- Teacher Signature on Left --}}
                                @if ($teacherSignature)
                                    <img src="{{ asset('storage/' . $teacherSignature) }}" alt="Teacher Signature"
                                        class="h-16 mx-auto mb-2 object-contain">
                                @else
                                    <div class="h-16 mb-2"></div>
                                @endif
                                <div class="w-48 h-px bg-gray-400 mx-auto mb-2"></div>
                                <p class="font-semibold" style="color: {{ $textColor }};">{{ $teacher->name }}</p>
                                <p class="text-sm text-gray-500">ผู้สอน</p>
                            @elseif($adminPosition === 'left')
                                {{-- Admin Signature on Left --}}
                                @if ($template->admin_signature)
                                    <img src="{{ asset('storage/' . $template->admin_signature) }}" alt="Admin Signature"
                                        class="h-16 mx-auto mb-2 object-contain">
                                @else
                                    <div class="h-16 mb-2"></div>
                                @endif
                                <div class="w-48 h-px bg-gray-400 mx-auto mb-2"></div>
                                <p class="font-semibold" style="color: {{ $textColor }};">
                                    {{ $template->admin_name ?? 'ผู้อำนวยการ' }}</p>
                                <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                            @endif
                        </div>

                        {{-- Right Signature --}}
                        <div class="text-center">
                            @if ($teacherPosition === 'right' && $template->show_teacher_signature)
                                {{-- Teacher Signature on Right --}}
                                @if ($teacherSignature)
                                    <img src="{{ asset('storage/' . $teacherSignature) }}" alt="Teacher Signature"
                                        class="h-16 mx-auto mb-2 object-contain">
                                @else
                                    <div class="h-16 mb-2"></div>
                                @endif
                                <div class="w-48 h-px bg-gray-400 mx-auto mb-2"></div>
                                <p class="font-semibold" style="color: {{ $textColor }};">{{ $teacher->name }}</p>
                                <p class="text-sm text-gray-500">ผู้สอน</p>
                            @elseif($adminPosition === 'right')
                                {{-- Admin Signature on Right --}}
                                @if ($template->admin_signature)
                                    <img src="{{ asset('storage/' . $template->admin_signature) }}" alt="Admin Signature"
                                        class="h-16 mx-auto mb-2 object-contain">
                                @else
                                    <div class="h-16 mb-2"></div>
                                @endif
                                <div class="w-48 h-px bg-gray-400 mx-auto mb-2"></div>
                                <p class="font-semibold" style="color: {{ $textColor }};">
                                    {{ $template->admin_name ?? 'ผู้อำนวยการ' }}</p>
                                <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Fallback: Only Teacher Signature --}}
                    <div class="my-12">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">ผู้สอนโดย</p>
                        @if ($teacherSignature)
                            <img src="{{ asset('storage/' . $teacherSignature) }}" alt="Teacher Signature"
                                class="h-16 mx-auto mb-2 object-contain">
                        @else
                            <div class="h-12 mb-2"></div>
                        @endif
                        <div class="w-48 h-px bg-gray-400 mx-auto mb-2"></div>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $teacher->name }}</p>
                    </div>
                @endif

                <!-- Seal/Badge -->
                <div class="flex justify-center mb-8">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-xl"
                        style="background: linear-gradient(135deg, {{ $primaryColor }}, {{ $borderColor }});">
                        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
                            <i class="fas fa-check text-3xl" style="color: {{ $primaryColor }};"></i>
                        </div>
                    </div>
                </div>

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
            </div>

        </div>

        <!-- Actions -->
        <div class="bg-gray-50 dark:bg-gray-700 p-6 flex flex-wrap justify-center gap-4">
            <a href="{{ route('student.certificates.index') }}"
                class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับ
            </a>
            <a href="{{ route('student.certificates.download', $certificate->id) }}"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-lg transition">
                <i class="fas fa-download mr-2"></i>ดาวน์โหลด PDF
            </a>
            <form action="{{ route('student.certificates.regenerate', $certificate->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition"
                    onclick="return confirm('ต้องการสร้างใบประกาศนียบัตรใหม่ตาม Template ล่าสุดหรือไม่?')">
                    <i class="fas fa-sync-alt mr-2"></i>สร้าง PDF ใหม่
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-4 text-center text-sm text-gray-600 dark:text-gray-400"
            style="background-color: {{ $primaryColor }}22;">
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
