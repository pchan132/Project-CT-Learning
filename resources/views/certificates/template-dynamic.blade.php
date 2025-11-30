<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
        }

        .page {
            width: 297mm;
            height: 210mm;
            padding: 10mm;
            background: #f8f9fa;
        }

        .border-box {
            width: 100%;
            height: 100%;
            border: 6px solid {{ $template->border_color ?? '#d4af37' }};
            background: #fff;
        }

        .inner-border {
            margin: 6px;
            border: 2px solid {{ $template->border_color ?? '#d4af37' }};
            height: calc(100% - 12px);
            padding: 15px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table td {
            text-align: center;
            padding: 8px 0;
        }

        .logo-cell {
            padding-top: 10px;
        }

        .logo-img {
            width: 60px;
            height: auto;
        }

        .star-icon {
            font-size: 50px;
            color: {{ $template->primary_color ?? '#6366f1' }};
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            color: {{ $template->primary_color ?? '#6366f1' }};
            padding: 5px 0;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .divider {
            width: 120px;
            height: 3px;
            background: {{ $template->primary_color ?? '#6366f1' }};
            margin: 10px auto;
        }

        .label-text {
            font-size: 13px;
            color: #666;
            padding: 5px 0;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: {{ $template->text_color ?? '#1f2937' }};
            padding: 8px 40px;
            border-bottom: 2px solid {{ $template->primary_color ?? '#6366f1' }};
            display: inline-block;
        }

        .course-name {
            font-size: 20px;
            font-weight: bold;
            color: {{ $template->text_color ?? '#1f2937' }};
            padding: 5px 0;
        }

        .seal-circle {
            width: 60px;
            height: 60px;
            border: 4px solid {{ $template->primary_color ?? '#6366f1' }};
            border-radius: 30px;
            margin: 0 auto;
            line-height: 52px;
            font-size: 24px;
            color: {{ $template->primary_color ?? '#6366f1' }};
            font-weight: bold;
        }

        .signature-table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            padding: 5px 20px;
            vertical-align: bottom;
        }

        .sig-img {
            height: 40px;
            max-width: 120px;
        }

        .sig-line {
            width: 150px;
            border-bottom: 1px solid #333;
            margin: 3px auto;
        }

        .sig-name {
            font-size: 12px;
            font-weight: bold;
            color: {{ $template->text_color ?? '#1f2937' }};
        }

        .sig-position {
            font-size: 10px;
            color: #666;
        }

        .footer-text {
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="border-box">
            <div class="inner-border">
                <table class="content-table">
                    <!-- Logo -->
                    <tr>
                        <td class="logo-cell">
                            @if ($template->logo_image && file_exists(public_path('storage/' . $template->logo_image)))
                                <img src="{{ public_path('storage/' . $template->logo_image) }}" class="logo-img">
                            @else
                                <div class="star-icon">★</div>
                            @endif
                        </td>
                    </tr>

                    <!-- Title -->
                    <tr>
                        <td>
                            <div class="title">ใบประกาศนียบัตร</div>
                            <div class="subtitle">Certificate of Completion</div>
                            <div class="divider"></div>
                        </td>
                    </tr>

                    <!-- Student Name -->
                    <tr>
                        <td>
                            <div class="label-text">ขอมอบใบประกาศนียบัตรนี้ให้แก่</div>
                            <div class="student-name">{{ $student->name }}</div>
                        </td>
                    </tr>

                    <!-- Course -->
                    <tr>
                        <td>
                            <div class="label-text">สำเร็จการศึกษาจากคอร์ส</div>
                            <div class="course-name">{{ $course->title }}</div>
                        </td>
                    </tr>

                    <!-- Seal -->
                    <tr>
                        <td style="padding: 10px 0;">
                            <div class="seal-circle">✓</div>
                        </td>
                    </tr>

                    <!-- Signatures -->
                    <tr>
                        <td>
                            @php
                                $adminPos = $template->admin_signature_position ?? 'right';
                                $teacherPos = $template->teacher_signature_position ?? 'left';
                                $showTeacher = $template->show_teacher_signature ?? true;
                                $teacherSig = $course->teacher->signature_image ?? null;
                            @endphp

                            <table class="signature-table">
                                <tr>
                                    <!-- Left Signature -->
                                    <td>
                                        @if ($teacherPos === 'left' && $showTeacher)
                                            @if ($teacherSig && file_exists(public_path('storage/' . $teacherSig)))
                                                <img src="{{ public_path('storage/' . $teacherSig) }}"
                                                    class="sig-img"><br>
                                            @else
                                                <div style="height: 40px;"></div>
                                            @endif
                                            <div class="sig-line"></div>
                                            <div class="sig-name">{{ $course->teacher->name }}</div>
                                            <div class="sig-position">ผู้สอน</div>
                                        @elseif($adminPos === 'left')
                                            @if ($template->admin_signature && file_exists(public_path('storage/' . $template->admin_signature)))
                                                <img src="{{ public_path('storage/' . $template->admin_signature) }}"
                                                    class="sig-img"><br>
                                            @else
                                                <div style="height: 40px;"></div>
                                            @endif
                                            <div class="sig-line"></div>
                                            <div class="sig-name">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</div>
                                            <div class="sig-position">{{ $template->admin_position ?? 'ผู้รับรอง' }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Right Signature -->
                                    <td>
                                        @if ($teacherPos === 'right' && $showTeacher)
                                            @if ($teacherSig && file_exists(public_path('storage/' . $teacherSig)))
                                                <img src="{{ public_path('storage/' . $teacherSig) }}"
                                                    class="sig-img"><br>
                                            @else
                                                <div style="height: 40px;"></div>
                                            @endif
                                            <div class="sig-line"></div>
                                            <div class="sig-name">{{ $course->teacher->name }}</div>
                                            <div class="sig-position">ผู้สอน</div>
                                        @elseif($adminPos === 'right')
                                            @if ($template->admin_signature && file_exists(public_path('storage/' . $template->admin_signature)))
                                                <img src="{{ public_path('storage/' . $template->admin_signature) }}"
                                                    class="sig-img"><br>
                                            @else
                                                <div style="height: 40px;"></div>
                                            @endif
                                            <div class="sig-line"></div>
                                            <div class="sig-name">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</div>
                                            <div class="sig-position">{{ $template->admin_position ?? 'ผู้รับรอง' }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td>
                            <div class="footer-text">
                                {{ $certificate->certificate_number }} | ออกเมื่อ
                                {{ $certificate->issued_date->format('d/m/Y') }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
