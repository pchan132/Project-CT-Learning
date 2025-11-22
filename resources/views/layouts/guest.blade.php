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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Prompt (Thai) -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 for pop-ups (แทน alert() / confirm()) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        /* กำหนดค่าเริ่มต้นของ Tailwind & Theme สี Neon Tech */
        :root {
            --primary-color: #A855F7;
            /* Violet-500 */
            --secondary-color: #EC4899;
            /* Fuchsia-500 */
            --background-dark: #0A041C;
            /* พื้นหลังสีม่วงเข้มเกือบดำ */
            --card-dark: #1E1539;
            /* สีการ์ดที่เข้มกว่า */
            --text-light: #E5E7EB;
        }

        /* Style สำหรับปุ่มหลักที่มี Gradient */
        .btn-primary {
            background: linear-gradient(90deg, #A855F7 0%, #EC4899 100%);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(236, 72, 153, 0.4);
        }

        /* Style สำหรับ Navbar ที่มีความโปร่งแสง */
        .glass-nav {
            background-color: rgba(10, 4, 28, 0.9);
            /* background-dark + opacity */
            backdrop-filter: blur(8px);
        }

        /* Style for the container card (Tech Card look) */
        .tech-card-form {
            background-color: var(--card-dark);
            border: 1px solid rgba(168, 85, 247, 0.5);
            /* Violet border */
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5), 0 0 15px rgba(236, 72, 153, 0.2);
            /* Dual shadow */
        }

        /* Style for the primary gradient button */
        .btn-primary-neon {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
            /* Tailwind classes: text-white font-bold py-2 px-6 rounded-full */
        }

        .btn-primary-neon:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.6);
        }

        /* Override component styles for dark/neon appearance */
        .form-input-neon {
            background-color: #110D25;
            /* Darker than card */
            border-color: #5B21B6;
            /* Violet-700 */
            color: #E5E7EB;
            transition: all 0.2s;
            /* Tailwind classes: block mt-1 w-full rounded-md shadow-sm */
        }

        .form-input-neon:focus {
            border-color: var(--secondary-color) !important;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.4) !important;
            outline: none;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

        {{-- <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div> --}}

        <div class="w-full sm:max-w-4xl mx-auto px-4">

            {{-- Form Container --}}
            <div class="form-input-neon rounded-md shadow-sm block mt-1 w-full py-2 px-3">
                <div class="flex flex-col lg:flex-row">
                    {{-- รูปภาพด้านซ้าย --}}
                    <div
                        class="lg:w-2/5 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-700 dark:to-gray-600 p-8 flex items-center justify-center">
                        <div class="text-center">
                            <x-application-logo class="w-24 h-24 mx-auto mb-4 text-blue-600 dark:text-blue-400" />
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">ยินดีต้อนรับ</h3>
                            <p class="text-gray-600 dark:text-gray-300">เข้าสู่ระบบเพื่อเริ่มการเรียนรู้</p>
                        </div>
                    </div>
                    {{-- ฟอร์มด้านขวา --}}
                    <div class="lg:w-3/5 p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
