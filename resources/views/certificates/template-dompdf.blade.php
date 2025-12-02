<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @php
        $primaryColor = $template->primary_color ?? '#c9a227';
        $secondaryColor = $template->border_color ?? '#c9a227';
        $textColor = $template->text_color ?? '#333333';
    @endphp
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        body {
            font-family: notosansthai, sans-serif;
            background: #fff;
            color: {{ $textColor }};
            margin: 0;
            padding: 0;
            position: relative;
            width: 297mm;
            height: 210mm;
        }

        /* === WAVE DECORATIONS === */
        .wave-tl-1 {
            position: absolute;
            top: 0;
            left: 0;
            width: 75mm;
            height: 32mm;
            background: {{ $primaryColor }};
            border-radius: 0 0 100% 0;
        }

        .wave-tl-2 {
            position: absolute;
            top: 0;
            left: 0;
            width: 55mm;
            height: 22mm;
            background: #fff;
            border-radius: 0 0 100% 0;
        }

        .wave-tl-3 {
            position: absolute;
            top: 0;
            left: 0;
            width: 35mm;
            height: 15mm;
            background: {{ $primaryColor }};
            border-radius: 0 0 100% 0;
        }

        .wave-tr-1 {
            position: absolute;
            top: 0;
            right: 0;
            width: 75mm;
            height: 32mm;
            background: {{ $primaryColor }};
            border-radius: 0 0 0 100%;
        }

        .wave-tr-2 {
            position: absolute;
            top: 0;
            right: 0;
            width: 55mm;
            height: 22mm;
            background: #fff;
            border-radius: 0 0 0 100%;
        }

        .wave-tr-3 {
            position: absolute;
            top: 0;
            right: 0;
            width: 35mm;
            height: 15mm;
            background: {{ $primaryColor }};
            border-radius: 0 0 0 100%;
        }

        .wave-bl-1 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 75mm;
            height: 32mm;
            background: {{ $primaryColor }};
            border-radius: 0 100% 0 0;
        }

        .wave-bl-2 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 55mm;
            height: 22mm;
            background: #fff;
            border-radius: 0 100% 0 0;
        }

        .wave-bl-3 {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 35mm;
            height: 15mm;
            background: {{ $primaryColor }};
            border-radius: 0 100% 0 0;
        }

        .wave-br-1 {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 75mm;
            height: 32mm;
            background: {{ $primaryColor }};
            border-radius: 100% 0 0 0;
        }

        .wave-br-2 {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 55mm;
            height: 22mm;
            background: #fff;
            border-radius: 100% 0 0 0;
        }

        .wave-br-3 {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 35mm;
            height: 15mm;
            background: {{ $primaryColor }};
            border-radius: 100% 0 0 0;
        }

        /* === GOLD FRAME === */
        .gold-frame {
            position: absolute;
            top: 15mm;
            left: 55mm;
            right: 55mm;
            bottom: 15mm;
            border: 3px solid {{ $secondaryColor }};
        }

        /* === LOGO === */
        .logo-section {
            position: absolute;
            top: 20mm;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .logo-img {
            max-width: 25mm;
            max-height: 25mm;
        }

        /* === MAIN CONTENT === */
        .main-content {
            position: absolute;
            top: 48mm;
            left: 60mm;
            right: 60mm;
            text-align: center;
        }

        .title-cert {
            font-size: 38pt;
            font-weight: bold;
            color: {{ $primaryColor }};
            letter-spacing: 5px;
        }

        .title-of {
            font-size: 10pt;
            color: {{ $primaryColor }};
            margin-top: 1mm;
        }

        .title-sub {
            font-size: 11pt;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: bold;
        }

        .presented-line {
            width: 75%;
            margin: 8mm auto 0 auto;
            border-bottom: 1px solid {{ $secondaryColor }};
            padding-bottom: 2mm;
        }

        .presented {
            font-size: 8pt;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .student-name {
            font-size: 28pt;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin-top: 4mm;
            font-style: italic;
        }

        .name-underline {
            width: 55%;
            margin: 0 auto;
            border-bottom: 2px solid {{ $secondaryColor }};
        }

        .description {
            font-size: 14pt;
            color: {{ $textColor }};
            line-height: 1.8;
            margin-top: 6mm;
            padding: 0 10mm;
        }

        .course-name {
            font-size: 16pt;
            font-weight: bold;
            color: {{ $primaryColor }};
        }

        /* === SIGNATURES === */
        .sig-section {
            position: absolute;
            bottom: 30mm;
            left: 70mm;
            right: 70mm;
        }

        .sig-table {
            width: 100%;
        }

        .sig-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 10mm;
        }

        .sig-img {
            width: 30mm;
            height: auto;
            max-height: 15mm;
        }

        .sig-line {
            width: 40mm;
            border-top: 1px solid #333333;
            margin: 3mm auto;
        }

        .sig-name {
            font-size: 14pt;
            font-weight: bold;
            color: {{ $textColor }};
        }

        .sig-position {
            font-size: 12pt;
            color: #666666;
        }

        /* === FOOTER === */
        .footer {
            position: absolute;
            bottom: 8mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }
    </style>
</head>

<body>

    <!-- Wave Decorations -->
    <div class="wave-tl-1"></div>
    <div class="wave-tl-2"></div>
    <div class="wave-tl-3"></div>

    <div class="wave-tr-1"></div>
    <div class="wave-tr-2"></div>
    <div class="wave-tr-3"></div>

    <div class="wave-bl-1"></div>
    <div class="wave-bl-2"></div>
    <div class="wave-bl-3"></div>

    <div class="wave-br-1"></div>
    <div class="wave-br-2"></div>
    <div class="wave-br-3"></div>

    <!-- Gold Frame -->
    <div class="gold-frame"></div>

    <!-- Logo (Center Top) -->
    @php
        $logoPath = $template->logo_image ? public_path('storage/' . $template->logo_image) : null;
        $logoBase64 = null;
        if ($logoPath && file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    <div class="logo-section">
        @if ($logoBase64)
            <img class="logo-img" src="{{ $logoBase64 }}">
        @endif
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="title-cert">CERTIFICATE</div>
        <div class="title-of">OF</div>
        <div class="title-sub">ACHIEVEMENT</div>

        <div class="presented-line">
            <span class="presented">This Certificate is Proudly Presented To</span>
        </div>

        <div class="student-name">{{ $student->name }}</div>
        <div class="name-underline"></div>

        <div class="description">
            @php
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
            @endphp
            ได้ผ่านการอบรมและทดสอบความรู้ในหลักสูตร
            <span class="course-name">"{{ $course->title }}"</span>
            <br>
            ซึ่งจัดโดย {{ $template->name ?? 'CT Learning' }}
            เมื่อวันที่ {{ $issuedDate->day }} {{ $thaiMonths[$issuedDate->month] }} พ.ศ.
            {{ $issuedDate->year + 543 }}
        </div>
    </div>

    <!-- Signatures -->
    @php
        $teacherSigPath =
            $course->teacher->signature_image ?? null
                ? public_path('storage/' . $course->teacher->signature_image)
                : null;
        $adminSigPath =
            $template->admin_signature ?? null ? public_path('storage/' . $template->admin_signature) : null;

        $teacherSigBase64 = null;
        $adminSigBase64 = null;

        if ($teacherSigPath && file_exists($teacherSigPath)) {
            $teacherSigBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($teacherSigPath));
        }
        if ($adminSigPath && file_exists($adminSigPath)) {
            $adminSigBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($adminSigPath));
        }
    @endphp

    <div class="sig-section">
        <table class="sig-table">
            <tr>
                {{-- Left: Teacher Signature --}}
                <td>
                    @if ($teacherSigBase64)
                        <img class="sig-img" src="{{ $teacherSigBase64 }}">
                    @else
                        <div style="height: 15mm;"></div>
                    @endif
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $course->teacher->name ?? '' }}</div>
                    <div class="sig-position">ผู้สอน</div>
                </td>

                {{-- Right: Admin Signature --}}
                <td>
                    @if ($adminSigBase64)
                        <img class="sig-img" src="{{ $adminSigBase64 }}">
                    @else
                        <div style="height: 15mm;"></div>
                    @endif
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $template->admin_name ?? 'ผู้อำนวยการ' }}</div>
                    <div class="sig-position">{{ $template->admin_position ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Certificate No: {{ $certificate->certificate_number }}
    </div>

</body>

</html>
