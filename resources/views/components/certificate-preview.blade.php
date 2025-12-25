{{-- Certificate Preview Component --}}
{{-- ใช้สำหรับแสดงตัวอย่าง Certificate ใน Admin, Teacher และ Student --}}

@props([
    'template' => null,
    'studentName' => 'ชื่อ-นามสกุล นักเรียน',
    'courseName' => 'ชื่อคอร์สเรียน',
    'teacherName' => 'อาจารย์ผู้สอน',
    'teacherSignature' => null,
    'teacherBackground' => null,
    'certificateNumber' => 'CERT-XXXXXX',
    'issuedDate' => null,
    'containerId' => 'certificate-preview',
])

@php
    // กำหนดค่าเริ่มต้นจาก template หรือใช้ค่า default
    $primaryColor = $template->primary_color ?? '#2563eb';
    $goldColor = $template->border_color ?? '#ca8a04';
    $textColor = $template->text_color ?? '#1f2937';

    // Background image - ใช้พื้นหลังของ Teacher ก่อน ถ้าไม่มีใช้ของ Template (Admin)
    // teacherBackground อาจเป็น full URL หรือ path
    $backgroundPath = $teacherBackground ?? ($template->background_image ?? null);
    $hasBackgroundImage = !empty($backgroundPath);

    // ตรวจสอบว่าเป็น full URL หรือ path
    if ($hasBackgroundImage) {
        if (str_starts_with($backgroundPath, 'http://') || str_starts_with($backgroundPath, 'https://')) {
            $backgroundUrl = $backgroundPath;
        } else {
            $backgroundUrl = asset('storage/' . $backgroundPath);
        }
    } else {
        $backgroundUrl = null;
    }

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

    $date = $issuedDate ?? now();
    $thaiDate = $date->day . ' ' . $thaiMonths[$date->month] . ' พ.ศ. ' . ($date->year + 543);

    // Organization name from template
    $orgName = $template->name ?? 'CT Learning';

    // Admin signature
    $adminSignature = $template->admin_signature ?? null;
    $adminName = $template->admin_name ?? 'ผู้อำนวยการ';
    $adminPosition = $template->admin_position ?? 'ผู้รับรอง';
@endphp

<div id="{{ $containerId }}" class="relative bg-white shadow-2xl overflow-hidden text-center mx-auto"
    style="width: 1123px; height: 794px; font-family: 'Sarabun', sans-serif; {{ $hasBackgroundImage ? 'background-image: url(' . $backgroundUrl . '); background-size: cover; background-position: center;' : '' }}">

    @if (!$hasBackgroundImage)
        <!-- Decorative Left Border (only show if no background image) -->
        <div class="absolute left-0 top-0 bottom-0 w-4 h-full" style="background-color: {{ $primaryColor }};"></div>
        <div class="absolute left-4 top-0 bottom-0 w-2 h-full" style="background-color: {{ $goldColor }};"></div>

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
                {{ $studentName }}
            </h1>
        </div>

        <!-- Body Section -->
        <div class="space-y-6 my-auto">
            <p class="text-2xl text-gray-700 font-light leading-relaxed">
                ได้ผ่านการอบรมและทดสอบความรู้ในหลักสูตร
            </p>
            <h2 class="text-4xl font-bold py-2" style="color: {{ $primaryColor }};">
                "{{ $courseName }}"
            </h2>
            <p class="text-xl text-gray-600">
                ซึ่งจัดโดย <span class="font-semibold text-gray-800">{{ $orgName }}</span>
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
                            @if ($teacherSignature)
                                <img src="{{ $teacherSignature }}" alt="Signature" class="h-10 object-contain">
                            @else
                                {{-- No signature image --}}
                                <div class="h-10"></div>
                            @endif
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-800">{{ $teacherName }}</p>
                    <p class="text-sm text-gray-500">ผู้สอน</p>
                @elseif ($adminSignaturePosition === 'left')
                    <!-- Admin Signature (Left) -->
                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                        <div class="h-12 w-full flex items-end justify-center">
                            @if ($adminSignature)
                                <img src="{{ asset('storage/' . $adminSignature) }}" alt="Admin Signature"
                                    class="h-10 object-contain">
                            @endif
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-800">{{ $adminName }}</p>
                    <p class="text-sm text-gray-500">{{ $adminPosition }}</p>
                @else
                    <!-- Empty space when no left signature -->
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
            </div>

            <!-- Right Signature -->
            <div class="text-center w-64">
                @if ($showTeacherSignature && $teacherSignaturePosition === 'right')
                    <!-- Teacher Signature (Right) -->
                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                        <div class="h-12 w-full flex items-end justify-center">
                            @if ($teacherSignature)
                                <img src="{{ $teacherSignature }}" alt="Signature" class="h-10 object-contain">
                            @else
                                {{-- No signature image --}}
                                <div class="h-10"></div>
                            @endif
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-800">{{ $teacherName }}</p>
                    <p class="text-sm text-gray-500">ผู้สอน</p>
                @elseif ($adminSignaturePosition === 'right')
                    <!-- Admin Signature (Right) -->
                    <div class="border-b-2 border-gray-400 mb-3 pb-2">
                        <div class="h-12 w-full flex items-end justify-center">
                            @if ($adminSignature)
                                <img src="{{ asset('storage/' . $adminSignature) }}" alt="Admin Signature"
                                    class="h-10 object-contain">
                            @endif
                        </div>
                    </div>
                    <p class="text-lg font-bold text-gray-800">{{ $adminName }}</p>
                    <p class="text-sm text-gray-500">{{ $adminPosition }}</p>
                @else
                    <!-- Empty space when no right signature -->
                    <div class="h-24"></div>
                @endif
            </div>
        </div>

        <!-- Certificate Number -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
            <p class="text-sm text-gray-400">Certificate No: {{ $certificateNumber }}</p>
        </div>
    </div>
</div>
