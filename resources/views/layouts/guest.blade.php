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
        body {
            font-family: 'Prompt', sans-serif;
        }

        /* --- RGB BORDER ANIMATION --- */
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .rgb-spinner {
            background: conic-gradient(from 0deg,
                    transparent 0deg,
                    #ff4545 60deg,
                    #00ff99 120deg,
                    #006aff 180deg,
                    #ff0095 240deg,
                    #ff4545 300deg,
                    transparent 360deg);
            /* ปรับ gradient ให้มีช่วง transparent เพื่อให้ดูเหมือนแสงวิ่งไล่กัน */
            background: conic-gradient(#ff4545, #00ff99, #006aff, #ff0095, #ff4545);
            animation: spin-slow 4s linear infinite;
            filter: blur(10px);
            /* เพิ่ม blur ให้แสงดูฟุ้งๆ เหมือนนีออน */
            opacity: 0.8;
        }

        /* ลดความเร็วตอน Hover */
        .group:hover .rgb-spinner {
            animation-duration: 2s;
        }

        .glass-input {
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            transform: scale(1.01);
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.4);
        }

        /* Animation Background */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-black text-gray-100 antialiased overflow-hidden">

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">

        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1538370965046-79c0d6907d47?q=80&w=2069&auto=format&fit=crop"
                alt="Galaxy Background" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/60 to-black/90"></div>
        </div>

        <!-- Decorative Blobs -->
        <div
            class="absolute top-20 left-20 w-64 h-64 bg-purple-600/30 rounded-full mix-blend-screen filter blur-3xl animate-pulse">
        </div>
        <div class="absolute bottom-20 right-20 w-64 h-64 bg-blue-600/30 rounded-full mix-blend-screen filter blur-3xl animate-pulse"
            style="animation-delay: 2s;"></div>


        <!-- Form Wrapper -->
        <div class="w-full sm:max-w-4xl mx-auto px-4 relative z-10 animate-float group">

            <!-- RGB Border Container -->
            <!-- ใช้ p-[3px] เพื่อกำหนดความหนาของเส้นขอบไฟวิ่ง -->
            <div class="relative rounded-3xl overflow-hidden p-[3px] shadow-[0_0_50px_-12px_rgba(168,85,247,0.5)]">

                <!-- 1. The Spinning RGB Layer (พื้นหลังหมุน) -->
                <div class="absolute inset-[-50%] w-[200%] h-[200%] top-[-50%] left-[-50%] rgb-spinner"></div>

                <!-- 2. The Content Card (ทับอยู่ด้านบน) -->
                <div
                    class="relative bg-gray-900/90 backdrop-blur-xl rounded-[calc(1.5rem-3px)] overflow-hidden h-full border border-white/10">

                    <div class="flex flex-col lg:flex-row min-h-[500px]">
                        <!-- Left Side: Welcome / Logo -->
                        <div
                            class="lg:w-2/5 bg-gradient-to-br from-indigo-900/50 to-purple-900/50 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                            <!-- Grid decorative background -->
                            <div
                                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20">
                            </div>

                            <div class="relative z-10">
                                <div
                                    class="w-24 h-24 mx-auto mb-6 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-[0_0_15px_rgba(255,255,255,0.2)]">
                                    <!-- Mock Logo -->
                                    <i
                                        class="fa-solid fa-rocket text-4xl text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-2 tracking-wide">WELCOME BACK</h3>
                                <p class="text-blue-200/70 text-sm mb-8">
                                    เข้าสู่ระบบเพื่อเริ่มการเรียนรู้ในจักรวาลแห่งใหม่</p>

                                <!-- Decorative dots -->
                                <div class="flex gap-2 justify-center">
                                    <div class="w-2 h-2 rounded-full bg-blue-400 animate-bounce"></div>
                                    <div class="w-2 h-2 rounded-full bg-purple-400 animate-bounce delay-75"></div>
                                    <div class="w-2 h-2 rounded-full bg-pink-400 animate-bounce delay-150"></div>
                                </div>
                            </div>
                        </div>
                        {{-- ฟอร์มด้านขวา --}}
                        <div class="lg:w-3/5 p-8 lg:p-10 bg-black/20">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
