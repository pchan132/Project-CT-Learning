<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Quill Rich Text Editor (Open Source - No Limits) -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <!-- SortableJS for Drag and Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <!-- Quill Editor Configuration -->
    <style>
        /* Quill Editor Styles */
        .ql-container {
            font-family: 'Sarabun', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }

        .ql-editor {
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
            padding: 20px;
        }

        .ql-editor.ql-blank::before {
            font-style: normal;
            color: #9ca3af;
        }

        /* Dark Mode Support */
        .dark .ql-toolbar {
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .ql-container {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .dark .ql-editor {
            color: #f3f4f6;
        }

        .dark .ql-editor.ql-blank::before {
            color: #6b7280;
        }

        .dark .ql-stroke {
            stroke: #9ca3af;
        }

        .dark .ql-fill {
            fill: #9ca3af;
        }

        .dark .ql-picker-label {
            color: #9ca3af;
        }

        .dark .ql-picker-options {
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .ql-picker-item {
            color: #9ca3af;
        }

        .dark .ql-picker-item:hover {
            background-color: #4b5563;
            color: #f3f4f6;
        }

        .dark .ql-toolbar button:hover,
        .dark .ql-toolbar button.ql-active {
            background-color: #4b5563;
        }

        .dark .ql-toolbar button:hover .ql-stroke,
        .dark .ql-toolbar button.ql-active .ql-stroke {
            stroke: #60a5fa;
        }

        .dark .ql-toolbar button:hover .ql-fill,
        .dark .ql-toolbar button.ql-active .ql-fill {
            fill: #60a5fa;
        }

        .dark .ql-tooltip {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .dark .ql-tooltip input[type=text] {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .dark .ql-action::after,
        .dark .ql-remove::before {
            color: #60a5fa;
        }

        /* Lesson Content Display - Quill Output */
        .lesson-content {
            min-height: auto !important;
            max-height: none !important;
            padding: 0 !important;
            font-size: 1.125rem;
            line-height: 1.75;
            color: #374151;
        }

        .dark .lesson-content {
            color: #e5e7eb;
        }

        .lesson-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #111827;
        }

        .dark .lesson-content h1 {
            color: #f9fafb;
        }

        .lesson-content h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            color: #1f2937;
        }

        .dark .lesson-content h2 {
            color: #f3f4f6;
        }

        .lesson-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .dark .lesson-content h3 {
            color: #e5e7eb;
        }

        .lesson-content p {
            margin-bottom: 1rem;
        }

        .lesson-content a {
            color: #2563eb;
            text-decoration: underline;
        }

        .dark .lesson-content a {
            color: #60a5fa;
        }

        .lesson-content ul,
        .lesson-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .lesson-content ul li {
            list-style-type: disc;
            margin-bottom: 0.25rem;
        }

        .lesson-content ol li {
            list-style-type: decimal;
            margin-bottom: 0.25rem;
        }

        .lesson-content blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #6b7280;
            font-style: italic;
        }

        .dark .lesson-content blockquote {
            border-left-color: #60a5fa;
            color: #9ca3af;
        }

        .lesson-content pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1rem 0;
        }

        .dark .lesson-content pre {
            background-color: #1f2937;
        }

        .lesson-content code {
            background-color: #fef2f2;
            color: #dc2626;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }

        .dark .lesson-content code {
            background-color: #1f2937;
            color: #f87171;
        }

        .lesson-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        .lesson-content .ql-align-center {
            text-align: center;
        }

        .lesson-content .ql-align-right {
            text-align: right;
        }

        .lesson-content .ql-align-justify {
            text-align: justify;
        }

        .lesson-content .ql-indent-1 {
            padding-left: 3rem;
        }

        .lesson-content .ql-indent-2 {
            padding-left: 6rem;
        }

        .lesson-content .ql-indent-3 {
            padding-left: 9rem;
        }

        .lesson-content .ql-size-small {
            font-size: 0.875rem;
        }

        .lesson-content .ql-size-large {
            font-size: 1.5rem;
        }

        .lesson-content .ql-size-huge {
            font-size: 2rem;
        }

        .lesson-content .ql-video {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
    </style>

    <script>
        // Quill Global Configuration
        window.quillConfig = {
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    [{
                        'size': ['small', false, 'large', 'huge']
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'align': []
                    }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            theme: 'snow',
            placeholder: 'พิมพ์เนื้อหาบทเรียนที่นี่...',
        };

        // Image Upload Handler for Quill
        window.quillImageHandler = function(quillInstance) {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/jpeg,image/png,image/jpg,image/gif,image/webp');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (!file) return;

                // ตรวจสอบขนาดไฟล์ (2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('❌ รูปภาพต้องมีขนาดไม่เกิน 2MB\n\nขนาดไฟล์ปัจจุบัน: ' + (file.size / 1024 / 1024)
                        .toFixed(2) + ' MB');
                    return;
                }

                // ตรวจสอบประเภทไฟล์
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('❌ รูปภาพต้องเป็นไฟล์ประเภท: JPEG, PNG, JPG, GIF, WEBP เท่านั้น');
                    return;
                }

                // แสดง loading
                const range = quillInstance.getSelection(true);
                quillInstance.insertText(range.index, '⏳ กำลังอัปโหลดรูปภาพ...');

                try {
                    const formData = new FormData();
                    formData.append('image', file);

                    const response = await fetch('/teacher/lessons/upload-image', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    });

                    const result = await response.json();

                    // ลบ loading text
                    quillInstance.deleteText(range.index, '⏳ กำลังอัปโหลดรูปภาพ...'.length);

                    if (result.success) {
                        // แทรกรูปภาพ
                        quillInstance.insertEmbed(range.index, 'image', result.url);
                        quillInstance.setSelection(range.index + 1);
                    } else {
                        alert('❌ เกิดข้อผิดพลาด: ' + (result.message || 'ไม่สามารถอัปโหลดรูปภาพได้'));
                    }
                } catch (error) {
                    // ลบ loading text
                    quillInstance.deleteText(range.index, '⏳ กำลังอัปโหลดรูปภาพ...'.length);
                    console.error('Upload error:', error);
                    alert('❌ เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ กรุณาลองใหม่อีกครั้ง');
                }
            };
        };

        // Function to initialize Quill with image handler
        window.initQuillWithImageUpload = function(selector, options = {}) {
            const config = {
                ...window.quillConfig,
                ...options
            };
            const quill = new Quill(selector, config);

            // Override image handler
            quill.getModule('toolbar').addHandler('image', function() {
                window.quillImageHandler(quill);
            });

            return quill;
        };
    </script>

    <style>
        /* กำหนดฟอนต์ Inter สำหรับ Body */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        /* สไตล์สำหรับปุ่มสลับโหมด (SVG Icon) */
        .mode-toggle-icon {
            transition: all 0.3s ease-in-out;
        }

        /* สไตล์หลักสำหรับพื้นหลังและการเปลี่ยนสีอย่างนุ่มนวล */
        body {
            transition: background-color 0.5s, color 0.5s;
        }
    </style>

    <!-- JavaScript สำหรับการทำงานของปุ่มสลับโหมดมืด/สว่าง -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeToggle = document.getElementById('mode-toggle');
            const sunIcon = document.getElementById('sun-icon');
            const moonIcon = document.getElementById('moon-icon');
            const html = document.documentElement;

            // ฟังก์ชันตรวจสอบและตั้งค่าโหมดเริ่มต้น
            function initDarkMode() {
                // ตรวจสอบว่ามีการบันทึกค่าไว้ใน localStorage หรือไม่
                const savedMode = localStorage.getItem('darkMode');

                if (savedMode === 'true') {
                    html.classList.add('dark');
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                } else if (savedMode === 'false') {
                    html.classList.remove('dark');
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    // ถ้าไม่มีการบันทึกไว้ ให้ตรวจสอบการตั้งค่าของระบบ
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (prefersDark) {
                        html.classList.add('dark');
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                    } else {
                        html.classList.remove('dark');
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    }
                }
            }

            // ฟังก์ชันสลับโหมด
            function toggleDarkMode() {
                const isDark = html.classList.contains('dark');

                if (isDark) {
                    // สลับไปโหมดสว่าง
                    html.classList.remove('dark');
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    // สลับไปโหมดมืด
                    html.classList.add('dark');
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                    localStorage.setItem('darkMode', 'true');
                }
            }

            // เรียกใช้ฟังก์ชันเริ่มต้น
            initDarkMode();

            // เพิ่ม event listener ให้ปุ่มสลับ
            if (modeToggle) {
                modeToggle.addEventListener('click', toggleDarkMode);
            }

            // ตรวจสอบการเปลี่ยนแปลงของการตั้งค่าระบบ
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                // อัปเดตเฉพาะกรณีที่ผู้ใช้ไม่ได้ตั้งค่าไว้
                if (localStorage.getItem('darkMode') === null) {
                    if (e.matches) {
                        html.classList.add('dark');
                        sunIcon.classList.add('hidden');
                        moonIcon.classList.remove('hidden');
                    } else {
                        html.classList.remove('dark');
                        sunIcon.classList.remove('hidden');
                        moonIcon.classList.add('hidden');
                    }
                }
            });
        });
    </script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @hasSection('header')
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-12">
            @yield('content')
        </main>
    </div>

    <!-- Additional Scripts Stack -->
    @stack('scripts')
</body>

</html>
