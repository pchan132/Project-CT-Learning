@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        ใบประกาศนียบัตร
    </h2>
@endsection

@php
    // ดึง template จาก certificate หรือใช้ template ที่ active
    $template = $certificate->template ?? \App\Models\CertificateTemplate::getActiveTemplate();

    // กำหนดสี Blue + Gold theme
    $primaryColor = $template->primary_color ?? '#2563eb';
    $goldColor = $template->border_color ?? '#ca8a04';
    $textColor = $template->text_color ?? '#1f2937';

    // ครูผู้สอน
    $teacher = $certificate->course->teacher;
    $teacherSignature = $teacher->signature_image ?? null;

    // Background image - ใช้พื้นหลังของ Teacher ก่อน ถ้าไม่มีใช้ของ Template (Admin)
    $teacherBackground = $teacher->certificate_background ?? null;
    $templateBackground = $template->background_image ?? null;
    $backgroundImage = $teacherBackground ?? $templateBackground;
    $hasBackgroundImage = !empty($backgroundImage);

    // ตำแหน่งลายเซ็น (ดึงจาก template)
    $showTeacherSignature = $template->show_teacher_signature ?? true;
    $teacherSignaturePosition = $template->teacher_signature_position ?? 'left';
    $adminSignaturePosition = $template->admin_signature_position ?? 'right';

    // Signature size - ขนาดลายเซ็น
    $sigHeight = '100px';
    $sigContainerHeight = '120px';

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
    $issuedDate = $certificate->issued_date;
    $thaiDate = $issuedDate->day . ' ' . $thaiMonths[$issuedDate->month] . ' พ.ศ. ' . ($issuedDate->year + 543);
@endphp

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600;700&family=Sarabun:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <style>
        .font-header {
            font-family: 'Chakra Petch', sans-serif;
        }

        .font-sarabun {
            font-family: 'Sarabun', sans-serif;
        }

        .seal-outer {
            background: linear-gradient(135deg, #fbbf24, #d97706);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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

        <!-- Certificate Container (A4 Landscape: 1123px x 794px) -->
        <div class="overflow-x-auto pb-4">
            <div id="certificate-container"
                class="relative bg-white shadow-2xl overflow-hidden text-center mx-auto font-sarabun"
                style="width: 1123px; height: 794px; {{ $hasBackgroundImage ? 'background-image: url(' . asset('storage/' . $backgroundImage) . '); background-size: cover; background-position: center;' : '' }}">

                @if (!$hasBackgroundImage)
                    <!-- Decorative Left Border (only show if no background image) -->
                    <div class="absolute left-0 top-0 bottom-0 w-4 h-full" style="background-color: {{ $primaryColor }};">
                    </div>
                    <div class="absolute left-4 top-0 bottom-0 w-2 h-full" style="background-color: {{ $goldColor }};">
                    </div>

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
                        <h1 class="text-6xl font-header font-bold drop-shadow-sm mt-2" style="color: {{ $goldColor }};">
                            {{ $certificate->student->name }}
                        </h1>
                    </div>

                    <!-- Body Section -->
                    <div class="space-y-6 my-auto">
                        <p class="text-2xl text-gray-700 font-light leading-relaxed">
                            ได้ผ่านการอบรมและทดสอบความรู้ในหลักสูตร
                        </p>
                        <h2 class="text-4xl font-bold py-2" style="color: {{ $primaryColor }};">
                            "{{ $certificate->course->title }}"
                        </h2>
                        <p class="text-xl text-gray-600">
                            ซึ่งจัดโดย <span
                                class="font-semibold text-gray-800">{{ $teacher->name ?? 'CT Learning' }}</span>
                            <br>
                            เมื่อวันที่ {{ $thaiDate }}
                        </p>
                    </div>

                    <!-- Footer / Signatures / Seal -->
                    <div class="relative w-full flex justify-between items-end mt-12">

                        {{-- Left Signature (ตามตำแหน่งที่กำหนดใน template) --}}
                        <div class="text-center w-64">
                            @if ($teacherSignaturePosition == 'left' && $showTeacherSignature)
                                {{-- ลายเซ็นครูผู้สอน --}}
                                <div
                                    style="display: flex; align-items: flex-end; justify-content: center; height: {{ $sigContainerHeight }}; border-bottom: 2px solid #9ca3af; margin-bottom: 4px;">
                                    @if ($teacherSignature)
                                        <img src="{{ asset('storage/' . $teacherSignature) }}" alt="Signature"
                                            style="height: {{ $sigHeight }}; max-width: 180px; object-fit: contain; display: block;">
                                    @endif
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $teacher->name ?? 'ผู้สอน' }}</p>
                                <p class="text-sm text-gray-500">ผู้สอน</p>
                            @elseif ($adminSignaturePosition == 'left')
                                {{-- ลายเซ็น Admin --}}
                                <div
                                    style="display: flex; align-items: flex-end; justify-content: center; height: {{ $sigContainerHeight }}; border-bottom: 2px solid #9ca3af; margin-bottom: 4px;">
                                    @if ($template && $template->admin_signature)
                                        <img src="{{ asset('storage/' . $template->admin_signature) }}"
                                            alt="Admin Signature"
                                            style="height: {{ $sigHeight }}; max-width: 180px; object-fit: contain; display: block;">
                                    @endif
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</p>
                                <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                            @endif
                        </div>

                        <!-- Center Seal -->
                        {{-- <div class="absolute left-1/2 transform -translate-x-1/2 bottom-4">
                            <div class="relative inline-flex justify-center items-center">
                                <div
                                    class="seal-outer w-32 h-32 rounded-full flex items-center justify-center border-4 border-yellow-200">
                                    <div
                                        class="w-24 h-24 border-2 border-dashed border-white rounded-full flex flex-col items-center justify-center text-white">
                                        <span class="text-lg font-bold tracking-wider">BEST</span>
                                        <span class="text-sm font-light">AWARD</span>
                                        <div class="w-8 h-8 mt-1">
                                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full text-white">
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
                        </div> --}}

                        {{-- Right Signature (ตามตำแหน่งที่กำหนดใน template) --}}
                        <div class="text-center w-64">
                            @if ($teacherSignaturePosition == 'right' && $showTeacherSignature)
                                {{-- ลายเซ็นครูผู้สอน --}}
                                <div
                                    style="display: flex; align-items: flex-end; justify-content: center; height: {{ $sigContainerHeight }}; border-bottom: 2px solid #9ca3af; margin-bottom: 4px;">
                                    @if ($teacherSignature)
                                        <img src="{{ asset('storage/' . $teacherSignature) }}" alt="Signature"
                                            style="height: {{ $sigHeight }}; max-width: 180px; object-fit: contain; display: block;">
                                    @endif
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $teacher->name ?? 'ผู้สอน' }}</p>
                                <p class="text-sm text-gray-500">ผู้สอน</p>
                            @elseif ($adminSignaturePosition == 'right')
                                {{-- ลายเซ็น Admin --}}
                                <div
                                    style="display: flex; align-items: flex-end; justify-content: center; height: {{ $sigContainerHeight }}; border-bottom: 2px solid #9ca3af; margin-bottom: 4px;">
                                    @if ($template && $template->admin_signature)
                                        <img src="{{ asset('storage/' . $template->admin_signature) }}"
                                            alt="Admin Signature"
                                            style="height: {{ $sigHeight }}; max-width: 180px; object-fit: contain; display: block;">
                                    @endif
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</p>
                                <p class="text-sm text-gray-500">{{ $template->admin_position ?? 'ผู้รับรอง' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Certificate Number -->
                    {{-- <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2">
                        <p class="text-sm text-gray-400">Certificate No: {{ $certificate->certificate_number }}</p>
                    </div> --}}
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 flex flex-wrap justify-center gap-4 rounded-xl">
                <a href="{{ route('student.certificates.index') }}"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-semibold transition">
                    <i class="fas fa-arrow-left mr-2"></i>กลับ
                </a>
                <button type="button" id="previewPdfBtn"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-lg transition"
                    data-certificate-id="{{ $certificate->id }}">
                    <i class="fas fa-eye mr-2"></i>ดูตัวอย่าง PDF
                </button>
                <button type="button" id="downloadPdfBtn"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-lg transition"
                    data-certificate-id="{{ $certificate->id }}">
                    <i class="fas fa-download mr-2"></i>ดาวน์โหลด PDF
                </button>
            </div>

            <!-- Course Details -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>รายละเอียดรายวิชา
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
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $certificate->course->total_lessons }}
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const previewBtn = document.getElementById('previewPdfBtn');
                const downloadBtn = document.getElementById('downloadPdfBtn');

                if (previewBtn) {
                    previewBtn.addEventListener('click', async function() {
                        const certId = this.dataset.certificateId;
                        const originalText = this.innerHTML;

                        try {
                            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังสร้าง...';
                            this.disabled = true;

                            await window.CertificatePDF.preview(certId);
                        } catch (error) {
                            console.error('Preview failed:', error);
                            alert('ไม่สามารถสร้าง PDF ได้ กรุณาลองใหม่อีกครั้ง');
                        } finally {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }
                    });
                }

                if (downloadBtn) {
                    downloadBtn.addEventListener('click', async function() {
                        const certId = this.dataset.certificateId;
                        const originalText = this.innerHTML;

                        try {
                            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังดาวน์โหลด...';
                            this.disabled = true;

                            await window.CertificatePDF.download(certId);
                        } catch (error) {
                            console.error('Download failed:', error);
                            alert('ไม่สามารถดาวน์โหลด PDF ได้ กรุณาลองใหม่อีกครั้ง');
                        } finally {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }
                    });
                }
            });
        </script>
    @endpush
