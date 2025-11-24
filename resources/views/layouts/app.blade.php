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
</body>

</html>
