<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', sans-serif;
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        }

        .certificate-container {
            width: 100%;
            height: 100vh;
            padding: 50px;
            box-sizing: border-box;
            position: relative;
        }

        .certificate-border {
            border: 15px solid #d4af37;
            padding: 40px;
            height: 100%;
            box-sizing: border-box;
            position: relative;
            background: white;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #d4af37;
            margin: 20px 0;
        }

        .certificate-subtitle {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }

        .certificate-body {
            text-align: center;
            padding: 30px 0;
        }

        .presented-to {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }

        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding: 10px 50px;
        }

        .course-description {
            font-size: 16px;
            color: #666;
            margin: 30px 0 10px 0;
        }

        .course-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0 30px 0;
        }

        .instructor {
            font-size: 14px;
            color: #666;
            margin-bottom: 40px;
        }

        .certificate-footer {
            display: table;
            width: 100%;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #ddd;
        }

        .footer-column {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .certificate-number {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #999;
        }

        .date {
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        .label {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }

        .seal {
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 10px solid #d4af37;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .decorative-line {
            width: 200px;
            height: 2px;
            background: linear-gradient(to right, transparent, #d4af37, transparent);
            margin: 20px auto;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-header">
                <div style="font-size: 60px; color: #d4af37; margin-bottom: 10px;">★</div>
                <div class="certificate-title">ใบประกาศนียบัตร</div>
                <div class="certificate-subtitle">Certificate of Completion</div>
                <div class="decorative-line"></div>
            </div>

            <div class="certificate-body">
                <div class="presented-to">ขอมอบใบประกาศนียบัตรนี้ให้แก่</div>
                <div class="student-name">{{ $student->name }}</div>

                <div class="course-description">สำเร็จการศึกษาจากคอร์ส</div>
                <div class="course-title">{{ $course->title }}</div>

                <div class="instructor">ผู้สอนโดย {{ $course->teacher->name }}</div>
            </div>

            <div class="certificate-footer">
                <div class="footer-column">
                    <div class="label">เลขที่ใบประกาศนียบัตร</div>
                    <div class="certificate-number">{{ $certificate->certificate_number }}</div>
                </div>
                <div class="footer-column">
                    <div class="label">วันที่ออก</div>
                    <div class="date">{{ $certificate->issued_date->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="seal">★</div>
        </div>
    </div>
</body>

</html>
