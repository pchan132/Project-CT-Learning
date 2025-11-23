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

    <!-- โหลด Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- ตั้งค่า Tailwind Configuration สำหรับ Dark Mode และใช้ Font Inter -->
    <script>
        tailwind.config = {
            darkMode: 'class', // ใช้คลาส 'dark' ใน <html> เพื่อสลับโหมด
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#1e3a8a', // Navy Blue เข้มสำหรับ Header
                        'background-light': '#f9fafb', // สีพื้นหลังขาวนวล
                        'card-light': '#ffffff', // สี Card สว่าง
                        'background-dark': '#111827', // สีพื้นหลังโหมดมืด
                        'card-dark': '#1f2937', // สี Card โหมดมืด
                        'accent-glow': '#3b82f6', // สีฟ้าสำหรับการเน้น
                    },
                    boxShadow: {
                        '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
                        'subtle-white': '0 4px 10px rgba(0, 0, 0, 0.05)',
                        'subtle-dark': '0 4px 10px rgba(255, 255, 255, 0.05)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
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

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
