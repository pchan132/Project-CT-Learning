@extends('layouts.app')

@section('title', 'ตัวอย่างใบประกาศนียบัตรพร้อมลายเซ็น')

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600;700&family=Sarabun:wght@300;400;600;700&display=swap"
        rel="stylesheet">
@endpush

@php
    // ดึง template ที่ active
    $template = \App\Models\CertificateTemplate::getActiveTemplate();

    // กำหนดค่าจาก template
    $primaryColor = $template->primary_color ?? '#2563eb';
    $goldColor = $template->border_color ?? '#ca8a04';
    $textColor = $template->text_color ?? '#1f2937';

    // Background image - ใช้พื้นหลังของ Teacher ก่อน ถ้าไม่มีใช้ของ Template (Admin)
    $teacherBackground = $teacher->certificate_background ?? null;
    $templateBackground = $template->background_image ?? null;
    $backgroundImage = $teacherBackground ?? $templateBackground;
    $hasBackgroundImage = !empty($backgroundImage);

    // ตำแหน่งลายเซ็น
    $showTeacherSignature = $template->show_teacher_signature ?? true;
    $teacherSignaturePosition = $template->teacher_signature_position ?? 'left';
    $adminSignaturePosition = $template->admin_signature_position ?? 'right';

    // Thai date
    $thaiMonths = [
        1 => 'มกราคม',
        2 => 'กุมภาพันธ์',
        3 => 'มีนาคม',
        4 => 'เมษายน',
        5 => 'พฤษภาคม',
        6 => 'มิถุนายน',
        7 => 'กรกฎาคม',
        8 => 'สิงหาคม',
        9 => 'กันยายน',
        10 => 'ตุลาคม',
        11 => 'พฤศจิกายน',
        12 => 'ธันวาคม',
    ];
    $date = now();
    $thaiDate = $date->day . ' ' . $thaiMonths[$date->month] . ' พ.ศ. ' . ($date->year + 543);
@endphp

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-certificate text-yellow-500 mr-3"></i>
                    ตัวอย่างใบประกาศนียบัตร
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    ดูตัวอย่างลายเซ็นของคุณบนใบประกาศนียบัตร
                    @if ($template)
                        (Template: <span class="font-semibold">{{ $template->name }}</span>)
                    @endif
                </p>
            </div>
            <a href="{{ route('teacher.signature.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>กลับ
            </a>
        </div>

        @if (!$teacher->signature_image)
            <div
                class="mb-6 bg-amber-100 dark:bg-amber-900/30 border border-amber-400 dark:border-amber-700 text-amber-700 dark:text-amber-300 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                คุณยังไม่ได้อัปโหลดลายเซ็น <a href="{{ route('teacher.signature.index') }}"
                    class="font-semibold underline">อัปโหลดลายเซ็นที่นี่</a>
            </div>
        @endif

        <!-- Certificate Preview -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <i class="fas fa-eye text-purple-500 mr-2"></i>ตัวอย่างใบประกาศนียบัตร
            </h3>

            <div class="overflow-x-auto pb-4">
                <div id="certificate-container" class="relative bg-white shadow-2xl overflow-hidden text-center mx-auto"
                    style="width: 1123px; height: 794px; font-family: 'Sarabun', sans-serif; {{ $hasBackgroundImage ? 'background-image: url(' . asset('storage/' . $backgroundImage) . '); background-size: cover; background-position: center;' : '' }}">

                    @if (!$hasBackgroundImage)
                        <!-- Decorative Left Border (only show if no background image) -->
                        <div class="absolute left-0 top-0 bottom-0 w-4 h-full"
                            style="background-color: {{ $primaryColor }};"></div>
                        <div class="absolute left-4 top-0 bottom-0 w-2 h-full"
                            style="background-color: {{ $goldColor }};"></div>

                        <!-- Decorative Corners -->
                        <div class="absolute top-0 right-0 w-32 h-32 rounded-bl-full -mr-16 -mt-16 opacity-50"
                            style="background-color: #fef3c7;"></div>
                        <div class="absolute bottom-0 right-0 w-48 h-48 rounded-tl-full -mr-10 -mb-10 opacity-50"
                            style="background-color: #dbeafe;"></div>
                    @endif

                    <!-- Main Content Area -->
                    <div class="relative z-10 h-full flex flex-col justify-between px-24 py-20">

                        <!-- Logo Section -->
                        <div class="flex justify-center mb-2">
                            @if ($template && $template->logo_image)
                                <img src="{{ asset('storage/' . $template->logo_image) }}" alt="Logo"
                                    class="h-20 w-auto object-contain">
                            @else
                                <div class="h-20 w-20 rounded-full flex items-center justify-center overflow-hidden"
                                    style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                                    <i class="fas fa-award text-3xl text-white"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Header Section -->
                        <div class="space-y-2 mt-2">
                            <p class="text-gray-500 text-lg tracking-widest uppercase">Certificate of Completion</p>
                            <h1 class="text-6xl font-bold drop-shadow-sm mt-2"
                                style="color: {{ $goldColor }}; font-family: 'Chakra Petch', sans-serif;">
                                นักเรียน ตัวอย่าง
                            </h1>
                        </div>

                        <!-- Body Section -->
                        <div class="space-y-6 my-auto">
                            <p class="text-2xl text-gray-700 font-light leading-relaxed">
                                ได้ผ่านการอบรมและทดสอบความรู้ในหลักสูตร
                            </p>
                            <h2 class="text-4xl font-bold py-2" style="color: {{ $primaryColor }};">
                                "ชื่อรายวิชาตัวอย่าง"
                            </h2>
                            <p class="text-xl text-gray-600">
                                ซึ่งจัดโดย <span
                                    class="font-semibold text-gray-800">{{ $template->name ?? 'CT Learning' }}</span>
                                <br>
                                เมื่อวันที่ {{ $thaiDate }}
                            </p>
                        </div>

                        <!-- Footer / Signatures / Seal -->
                        <div class="relative w-full flex justify-between items-end mt-12">

                            <!-- Left Signature -->
                            <div class="text-center w-64">
                                @if ($showTeacherSignature && $teacherSignaturePosition === 'left')
                                    <!-- Teacher Signature (Left) -->
                                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                                        <div class="h-12 w-full flex items-end justify-center">
                                            @if ($teacher->signature_image)
                                                <img src="{{ asset('storage/' . $teacher->signature_image) }}"
                                                    alt="Signature" class="h-10 object-contain">
                                            @else
                                                <div class="h-10 text-gray-400 italic text-sm">(ไม่มีลายเซ็น)</div>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800">{{ $teacher->name }}</p>
                                    <p class="text-sm text-gray-500">ผู้สอน</p>
                                @elseif($adminSignaturePosition === 'left' && $template)
                                    <!-- Admin Signature (Left) -->
                                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                                        <div class="h-12 w-full flex items-end justify-center">
                                            @if ($template->admin_signature)
                                                <img src="{{ asset('storage/' . $template->admin_signature) }}"
                                                    alt="Admin Signature" class="h-10 object-contain">
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                                @else
                                    <div class="h-24"></div>
                                @endif
                            </div>

                            <!-- Center Seal -->
                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-4">
                                <div class="relative inline-flex justify-center items-center">
                                    <div class="w-32 h-32 rounded-full flex items-center justify-center border-4 border-yellow-200"
                                        style="background: linear-gradient(135deg, #fbbf24, #d97706); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                        <div
                                            class="w-24 h-24 border-2 border-dashed border-white rounded-full flex flex-col items-center justify-center text-white">
                                            <span class="text-lg font-bold tracking-wider">BEST</span>
                                            <span class="text-sm font-light">AWARD</span>
                                            <div class="w-8 h-8 mt-1">
                                                <svg viewBox="0 0 24 24" fill="currentColor"
                                                    class="w-full h-full text-white">
                                                    <path
                                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Ribbon tails -->
                                    <div class="absolute -bottom-4 -z-10 flex gap-12">
                                        <div class="w-8 h-12 transform rotate-12" style="background-color: #ca8a04;"></div>
                                        <div class="w-8 h-12 transform -rotate-12" style="background-color: #ca8a04;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Signature -->
                            <div class="text-center w-64">
                                @if ($showTeacherSignature && $teacherSignaturePosition === 'right')
                                    <!-- Teacher Signature (Right) -->
                                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                                        <div class="h-12 w-full flex items-end justify-center">
                                            @if ($teacher->signature_image)
                                                <img src="{{ asset('storage/' . $teacher->signature_image) }}"
                                                    alt="Signature" class="h-10 object-contain">
                                            @else
                                                <div class="h-10 text-gray-400 italic text-sm">(ไม่มีลายเซ็น)</div>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800">{{ $teacher->name }}</p>
                                    <p class="text-sm text-gray-500">ผู้สอน</p>
                                @elseif($adminSignaturePosition === 'right' && $template)
                                    <!-- Admin Signature (Right) -->
                                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                                        <div class="h-12 w-full flex items-end justify-center">
                                            @if ($template->admin_signature)
                                                <img src="{{ asset('storage/' . $template->admin_signature) }}"
                                                    alt="Admin Signature" class="h-10 object-contain">
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-lg font-bold text-gray-800">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                                @else
                                    <div class="h-24"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Certificate Number -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                            <p class="text-sm text-gray-400">Certificate No:
                                CERT-PREVIEW-{{ strtoupper(substr(md5($teacher->id), 0, 8)) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                นี่คือตัวอย่างว่าลายเซ็นของคุณจะแสดงบนใบประกาศนียบัตรอย่างไร
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-4 justify-center">
            @if (!$teacher->signature_image)
                <a href="{{ route('teacher.signature.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-upload mr-2"></i>อัปโหลดลายเซ็น
                </a>
            @else
                <a href="{{ route('teacher.signature.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                    <i class="fas fa-check mr-2"></i>ลายเซ็นของคุณพร้อมใช้งานแล้ว!
                </a>
            @endif
        </div>
    </div>
@endsection
