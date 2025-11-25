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
