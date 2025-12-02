<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Page Setup - A4 Landscape */
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .certificate-wrapper {
            width: 100%;
            border: 4px solid {{ $template->border_color ?? '#d4af37' }};
            padding: 8px;
        }

        .certificate-inner {
            width: 100%;
            border: 2px solid {{ $template->border_color ?? '#d4af37' }};
            padding: 20px 30px;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 10px;
        }

        .title-th {
            font-size: 28pt;
            font-weight: bold;
            color: {{ $template->primary_color ?? '#d4af37' }};
            margin-bottom: 5px;
        }

        .title-en {
            font-size: 12pt;
            color: #666666;
            margin-bottom: 10px;
        }

        .divider {
            width: 100px;
            height: 3px;
            background-color: {{ $template->primary_color ?? '#d4af37' }};
            margin: 10px auto 15px auto;
        }

        .label {
            font-size: 11pt;
            color: #666666;
            margin-bottom: 5px;
        }

        .student-name {
            font-size: 22pt;
            font-weight: bold;
            color: {{ $template->text_color ?? '#333333' }};
            border-bottom: 2px solid {{ $template->primary_color ?? '#d4af37' }};
            display: inline-block;
            padding: 5px 40px;
            margin-bottom: 12px;
        }

        .course-label {
            font-size: 11pt;
            color: #666666;
            margin-bottom: 3px;
        }

        .course-name {
            font-size: 16pt;
            font-weight: bold;
            color: {{ $template->text_color ?? '#333333' }};
            margin-bottom: 12px;
        }

        .seal {
            width: 50px;
            height: 50px;
            border: 3px solid {{ $template->primary_color ?? '#d4af37' }};
            border-radius: 25px;
            margin: 8px auto;
            line-height: 44px;
            font-size: 18pt;
            color: {{ $template->primary_color ?? '#d4af37' }};
        }

        .sig-table {
            width: 70%;
            margin: 15px auto 0 auto;
        }

        .sig-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .sig-line {
            width: 120px;
            border-top: 1px solid #333333;
            margin: 5px auto 5px auto;
        }

        .sig-name {
            font-size: 10pt;
            font-weight: bold;
            color: {{ $template->text_color ?? '#333333' }};
        }

        .sig-position {
            font-size: 9pt;
            color: #666666;
        }

        .footer {
            margin-top: 12px;
            font-size: 8pt;
            color: #999999;
        }
    </style>
</head>

<body>
    <div class="certificate-wrapper">
        <div class="certificate-inner">

            {{-- Logo --}}
            <div class="logo-section">
                @php
                    $logoW = $template->logo_width ?? 70;
                    $logoH = $template->logo_height ?? 70;
                @endphp
                @if ($template->logo_image && file_exists(public_path('storage/' . $template->logo_image)))
                    <img src="{{ public_path('storage/' . $template->logo_image) }}"
                        style="width: {{ $logoW }}px; height: {{ $logoH }}px;">
                @endif
            </div>

            {{-- Title --}}
            <div class="title-th">ใบประกาศนียบัตร</div>
            <div class="title-en">Certificate of Completion</div>
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center;">
                        <div class="divider"></div>
                    </td>
                </tr>
            </table>

            {{-- Student Info --}}
            <div class="label">ขอมอบใบประกาศนียบัตรนี้ให้แก่</div>
            <div class="student-name">{{ $student->name }}</div>

            {{-- Course Info --}}
            <div class="course-label">สำเร็จการศึกษาจากคอร์ส</div>
            <div class="course-name">{{ $course->title }}</div>

            {{-- Seal --}}
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: center;">
                        <div class="seal">✓</div>
                    </td>
                </tr>
            </table>

            {{-- Signatures --}}
            @php
                $adminPos = $template->admin_signature_position ?? 'left';
                $teacherPos = $template->teacher_signature_position ?? 'right';
                $showTeacher = $template->show_teacher_signature ?? true;
                $teacherSig = $course->teacher->signature_image ?? null;

                $adminSigW = $template->admin_signature_width ?? 100;
                $adminSigH = $template->admin_signature_height ?? 40;
                $teacherSigW = $template->teacher_signature_width ?? 100;
                $teacherSigH = $template->teacher_signature_height ?? 40;
            @endphp

            <table class="sig-table">
                <tr>
                    {{-- Left Signature --}}
                    <td>
                        @if ($adminPos === 'left')
                            @if ($template->admin_signature && file_exists(public_path('storage/' . $template->admin_signature)))
                                <img src="{{ public_path('storage/' . $template->admin_signature) }}"
                                    style="width: {{ $adminSigW }}px; height: {{ $adminSigH }}px;">
                            @else
                                <div style="height: {{ $adminSigH }}px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</div>
                            <div class="sig-position">{{ $template->admin_position ?? 'ผู้รับรอง' }}</div>
                        @elseif($teacherPos === 'left' && $showTeacher)
                            @if ($teacherSig && file_exists(public_path('storage/' . $teacherSig)))
                                <img src="{{ public_path('storage/' . $teacherSig) }}"
                                    style="width: {{ $teacherSigW }}px; height: {{ $teacherSigH }}px;">
                            @else
                                <div style="height: {{ $teacherSigH }}px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $course->teacher->name }}</div>
                            <div class="sig-position">ผู้สอน</div>
                        @endif
                    </td>

                    {{-- Right Signature --}}
                    <td>
                        @if ($adminPos === 'right')
                            @if ($template->admin_signature && file_exists(public_path('storage/' . $template->admin_signature)))
                                <img src="{{ public_path('storage/' . $template->admin_signature) }}"
                                    style="width: {{ $adminSigW }}px; height: {{ $adminSigH }}px;">
                            @else
                                <div style="height: {{ $adminSigH }}px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</div>
                            <div class="sig-position">{{ $template->admin_position ?? 'ผู้รับรอง' }}</div>
                        @elseif($teacherPos === 'right' && $showTeacher)
                            @if ($teacherSig && file_exists(public_path('storage/' . $teacherSig)))
                                <img src="{{ public_path('storage/' . $teacherSig) }}"
                                    style="width: {{ $teacherSigW }}px; height: {{ $teacherSigH }}px;">
                            @else
                                <div style="height: {{ $teacherSigH }}px;"></div>
                            @endif
                            <div class="sig-line"></div>
                            <div class="sig-name">{{ $course->teacher->name }}</div>
                            <div class="sig-position">ผู้สอน</div>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Footer --}}
            <div class="footer">
                {{ $certificate->certificate_number }} | ออกเมื่อ {{ $certificate->issued_date->format('d/m/Y') }}
            </div>

        </div>
    </div>
</body>

</html>
