<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แผนกวิชาเทคโนโลยีคอมพิวเตอร์ - วิทยาลัยเทคนิคลพบุรี (Future Tech Style)</title>
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

        body {
            font-family: 'Prompt', sans-serif;
            /* ใช้ Prompt สำหรับภาษาไทย */
            background-color: var(--background-dark);
            color: var(--text-light);
            overflow-x: hidden;
            /* ป้องกันการเกิด scroll bar แนวขวางจาก animation */
        }

        /* Custom Styles for Particle Background Effect */
        #particle-bg {
            position: fixed;
            /* ใช้ fixed เพื่อให้ particles อยู่เบื้องหลังเสมอ */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.5;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(168, 85, 247, 0.4);
            box-shadow: 0 0 10px rgba(168, 85, 247, 0.8), 0 0 20px rgba(236, 72, 153, 0.6);
            animation: moveParticle linear infinite;
        }

        @keyframes moveParticle {
            0% {
                transform: translate(0, 0);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translate(var(--x), var(--y));
                opacity: 0;
            }
        }

        /* องค์ประกอบการ์ดที่ดูเทคโนโลยี มีขอบม่วง */
        .tech-card {
            background-color: var(--card-dark);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
        }

        .tech-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.2);
        }

        /* แอนิเมชันสำหรับการ Fade In และ Slide Up (ว้าวววว) */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .visible {
            opacity: 1;
            transform: translateY(0);
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
    </style>
</head>

<body class="selection:bg-violet-600 selection:text-white">

    <!-- Particle Background Container -->
    <div id="particle-bg"></div>




    <!-- Navbar (Glass Effect) -->
    <nav class="glass-nav sticky top-0 w-full z-50 shadow-xl">
        <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="#home" class="flex items-center">
                    <!-- ใช้ชื่อแผนกแทนโลโก้ -->
                    <span
                        class="text-xl md:text-2xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">
                        Center-Learning <span class="text-white">TECHNOLOGY COMPUTER</span>
                    </span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#home"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">หน้าหลัก</a>
                        <a href="#about"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">เกี่ยวกับแผนก</a>
                        <a href="#courses"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">หลักสูตร</a>
                        <a href="#teachers"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">อาจารย์ประจำแผนก</a>
                        <a href="#projects"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">คอรส์เรียน</a>
                        <a href="#contact"
                            class="nav-link text-gray-300 hover:text-violet-400 px-3 py-2 text-sm font-medium transition duration-150">ติดต่อ</a>

                        @if (Route::has('login'))
                            <div class="sm:top-0 sm:right-0 p-6 text-right z-10">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                                        in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-violet-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-violet-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden glass-nav shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#home"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">หน้าหลัก</a>
                <a href="#about"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">เกี่ยวกับแผนก</a>
                <a href="#courses"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">หลักสูตร</a>
                <a href="#teachers"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">อาจารย์ประจำแผนก</a>
                <a href="#projects"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">คอรส์เรียน</a>
                <a href="#contact"
                    class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-violet-400 hover:bg-violet-900/50">ติดต่อ</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="mobile-nav-link block px-3 py-2 rounded-md text-base font-medium text-violet-400 hover:text-violet-300 hover:bg-violet-900/50">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @else
                        <div class="pt-3 mt-3 border-t border-violet-700/50 space-y-2">
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2.5 rounded-lg text-center text-base font-semibold text-gray-300 bg-violet-800/50 hover:bg-violet-700/50 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>เข้าสู่ระบบ
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="block px-3 py-2.5 rounded-lg text-center text-base font-semibold text-white bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 transition-colors">
                                    <i class="fas fa-user-plus mr-2"></i>หลักสูตรที่เปิดสอน
                                </a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section: แผนกวิชาเทคโนโลยีคอมพิวเตอร์ -->
        <section id="home" class="relative overflow-hidden pt-24 pb-16 md:pt-32 md:pb-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">

                <!-- Text Content (Fade In Up) -->
                <div class="md:w-1/2 text-center md:text-left mb-12 md:mb-0 fade-in-up" data-delay="0">
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight tracking-tighter text-white">
                        แผนกวิชา<span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">เทคโนโลยีคอมพิวเตอร์</span>
                    </h1>
                    <p class="mt-4 text-xl text-gray-400 max-w-lg mx-auto md:mx-0">
                        วิทยาลัยเทคนิคลพบุรี — มุ่งผลิตบุคลากรแห่งอนาคต ด้วยทักษะดิจิทัลที่ล้ำหน้า
                    </p>
                    <p class="mt-6 text-gray-400 max-w-lg mx-auto md:mx-0">
                        มุ่งผลิตบุคลากรที่มีความรู้ความสามารถด้านเทคโนโลยีคอมพิวเตอร์
                        เพื่อตอบสนองความต้องการของตลาดแรงงานและสังคม
                    </p>

                    <div
                        class="mt-8 flex flex-col sm:flex-row justify-center md:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#courses"
                            class="btn-primary text-white font-semibold py-3 px-8 rounded-full shadow-xl shadow-violet-500/30">
                            <i class="fas fa-graduation-cap mr-2"></i> หลักสูตรที่เปิดสอน
                        </a>
                        <a href="#contact"
                            class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-8 rounded-full transition duration-300">
                            <i class="fas fa-envelope mr-2"></i> ติดต่อแผนกวิชา
                        </a>
                    </div>
                </div>

                <!-- Visual Element (Holographic/Tech Look) -->
                <div class="md:w-1/2 flex justify-center md:justify-end mt-8 md:mt-0">
                    <div id="hologram-visual" class="w-80 h-80 sm:w-96 sm:h-96 relative fade-in-up" data-delay="200">
                        <!-- Holographic Ring/Sphere Effect (หมุน) -->
                        <div class="absolute inset-0 rounded-full border-4 border-violet-500/50 animate-spin-slow">
                        </div>
                        <div
                            class="absolute inset-4 rounded-full border-4 border-fuchsia-500/50 animate-spin-reverse-slow">
                        </div>

                        <!-- Core Icon: Laptop (Icon ที่เหมาะสมกับแผนกคอมพิวเตอร์) -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-laptop-code text-9xl text-violet-400 opacity-80"
                                style="text-shadow: 0 0 15px rgba(168, 85, 247, 0.8);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
                <div class="tech-card p-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-center fade-in-up"
                    data-delay="400">
                    <div>
                        <p
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">
                            3</p>
                        <p class="text-sm text-gray-400">หลักสูตร</p>
                    </div>
                    <div>
                        <p
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">
                            10+</p>
                        <p class="text-sm text-gray-400">อาจารย์ประจำแผนก</p>
                    </div>
                    <div>
                        <p
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">
                            45</p>
                        <p class="text-sm text-gray-400">ห้องเรียน</p>
                    </div>
                    <div>
                        <p
                            class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500">
                            200+</p>
                        <p class="text-sm text-gray-400">ผลงานนักศึกษา</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="500">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white">เกี่ยวกับ<span class="text-fuchsia-400">แผนกวิชา</span>
                    </h2>
                    <div class="mt-4 h-1 w-24 bg-violet-500 mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="md:pr-10">
                        <h3 class="text-2xl font-semibold text-violet-400 mb-4">วิสัยทัศน์และพันธกิจ</h3>
                        <p class="text-gray-400 mb-6">
                            แผนกวิชาเทคโนโลยีคอมพิวเตอร์ วิทยาลัยเทคนิคลพบุรี
                            ก่อตั้งขึ้นเพื่อผลิตบุคลากรที่มีความรู้ความสามารถด้านเทคโนโลยีคอมพิวเตอร์อย่างลึกซึ้ง
                            ปัจจุบันเราได้พัฒนาหลักสูตรให้ทันสมัย สอดคล้องกับเทคโนโลยีที่เปลี่ยนแปลงอย่างรวดเร็ว
                            มุ่งเน้นการเรียนการสอนทั้งภาคทฤษฎีและปฏิบัติ
                        </p>

                        <div class="space-y-6">
                            <div class="tech-card p-4 flex items-start">
                                <i class="fas fa-eye text-2xl text-fuchsia-400 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-white">วิสัยทัศน์</h4>
                                    <p class="text-sm text-gray-400">มุ่งผลิตนักเทคโนโลยีที่มีความรู้คู่คุณธรรม
                                        ก้าวทันเทคโนโลยีแห่งโลกอนาคต</p>
                                </div>
                            </div>
                            <div class="tech-card p-4 flex items-start">
                                <i class="fas fa-rocket text-2xl text-fuchsia-400 mr-4"></i>
                                <div>
                                    <h4 class="font-semibold text-white">พันธกิจ</h4>
                                    <p class="text-sm text-gray-400">จัดการศึกษาด้านเทคโนโลยีคอมพิวเตอร์ที่มีคุณภาพ
                                        ตอบสนองความต้องการของตลาดแรงงานดิจิทัล</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-0">
                        <!-- Placeholder for About Image with Tech Overlay -->
                        <div class="relative rounded-xl overflow-hidden shadow-2xl shadow-violet-500/30">
                            <!-- ใช้ Placeholder แทนรูปภาพเดิม -->
                            <img src="https://placehold.co/600x400/1F1539/A855F7?text=Tech+Vision" alt="Tech Vision"
                                class="w-full h-auto object-cover opacity-80" />
                            <div
                                class="absolute inset-0 bg-violet-900/40 backdrop-blur-sm flex items-center justify-center">
                                <i class="fas fa-brain text-8xl text-fuchsia-400 opacity-70 animate-pulse"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section id="courses" class="py-24 bg-gray-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="600">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white">หลักสูตร<span class="text-violet-400">แห่งอนาคต</span>
                    </h2>
                    <div class="mt-4 h-1 w-24 bg-fuchsia-500 mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Course 1: ปวช. สาขาเทคโนโลยีคอมพิวเตอร์ -->
                    <div class="tech-card p-6 transition duration-500 hover:scale-[1.03]">
                        <div class="h-48 bg-violet-600/30 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-laptop-code text-white text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">ปวช. เทคโนโลยีคอมพิวเตอร์</h3>
                        <p class="text-gray-400 mb-4 text-sm">
                            รากฐานสู่โลกดิจิทัล สำหรับผู้จบ ม.3 เน้นการเขียนโปรแกรมเบื้องต้น ระบบคอมพิวเตอร์
                            และเครือข่ายพื้นฐาน
                        </p>
                        <button
                            class="course-details btn-primary text-white font-semibold py-2 px-6 rounded-full text-sm"
                            data-course="ปวช">
                            <i class="fas fa-arrow-right mr-2"></i> ดูรายละเอียด
                        </button>
                    </div>

                    <!-- Course 2: ปวส. สาขาคอมพิวเตอร์ฮาร์ดแวร์ -->
                    <div class="tech-card p-6 transition duration-500 hover:scale-[1.03]">
                        <div class="h-48 bg-indigo-600/30 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-server text-white text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">ปวส. คอมพิวเตอร์ฮาร์ดแวร์</h3>
                        <p class="text-gray-400 mb-4 text-sm">
                            เชี่ยวชาญด้านโครงสร้างและระบบเครือข่ายขั้นสูง การซ่อมบำรุงขั้นสูง และ IOT
                            ด้วยไมโครคอนโทรลเลอร์
                        </p>
                        <button
                            class="course-details btn-primary text-white font-semibold py-2 px-6 rounded-full text-sm"
                            data-course="ปวส-ฮาร์ดแวร์">
                            <i class="fas fa-arrow-right mr-2"></i> ดูรายละเอียด
                        </button>
                    </div>

                    <!-- Course 3: ปวส. สาขาคอมพิวเตอร์ซอฟต์แวร์ -->
                    <div class="tech-card p-6 transition duration-500 hover:scale-[1.03]">
                        <div class="h-48 bg-purple-600/30 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-code text-white text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">ปวส. คอมพิวเตอร์ซอฟต์แวร์</h3>
                        <p class="text-gray-400 mb-4 text-sm">
                            นักพัฒนาแห่งโลกดิจิทัล เน้นการเขียนโปรแกรมเชิงวัตถุ การพัฒนาเว็บ/โมบายล์แอปพลิเคชัน
                            และระบบฐานข้อมูล
                        </p>
                        <button
                            class="course-details btn-primary text-white font-semibold py-2 px-6 rounded-full text-sm"
                            data-course="ปวส-ซอฟต์แวร์">
                            <i class="fas fa-arrow-right mr-2"></i> ดูรายละเอียด
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Teachers Section -->
        <section id="teachers" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="700">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white">อาจารย์<span class="text-fuchsia-400">ประจำแผนก</span>
                    </h2>
                    <div class="mt-4 h-1 w-24 bg-violet-500 mx-auto rounded-full"></div>
                </div>

                <!-- ใช้ Grid 4 คอลัมน์สำหรับ Desktop และ 2 คอลัมน์สำหรับ Mobile -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                    <!-- คณาจารย์จะถูกจัดเรียงอัตโนมัติ 10 ท่าน -->
                    <!-- Teacher 1: อาจารย์รณภูมิ นาคสมบูรณ์ (หัวหน้าแผนก) -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=รณภูมิ" alt="อาจารย์รณภูมิ"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white"></h3>
                            <p class="text-xs text-fuchsia-400"></p>
                            <p class="text-xs text-gray-500 mt-1"></p>
                        </div>
                    </div>

                    <!-- Teacher 2: อาจารย์วิชัย เทคโนโลยี -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=วิชัย" alt="อาจารย์วิชัย"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">วิชัย เทคโนโลยี</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">การพัฒนาซอฟต์แวร์</p>
                        </div>
                    </div>

                    <!-- Teacher 3: อาจารย์สมศรี คอมพิวเตอร์ -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=สมศรี" alt="อาจารย์สมศรี"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">สมศรี คอมพิวเตอร์</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">ฮาร์ดแวร์คอมพิวเตอร์</p>
                        </div>
                    </div>

                    <!-- Teacher 4: อาจารย์มานะ พัฒนา -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=มานะ" alt="อาจารย์มานะ"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">มานะ พัฒนา</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">ระบบฐานข้อมูล</p>
                        </div>
                    </div>

                    <!-- Teacher 5: อาจารย์จิตวัฒน์ -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=จิตวัฒน์" alt="อาจารย์จิตวัฒน์"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">จิตวัฒน์ เปิ่นวงษ์</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">การเขียนโปรแกรม</p>
                        </div>
                    </div>

                    <!-- Teacher 6: อาจารย์กัญชิตา -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=กัญชิตา" alt="อาจารย์กัญชิตา"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">กัญชิตา ธูปแช่ม</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">การพัฒนาเว็บ</p>
                        </div>
                    </div>

                    <!-- Teacher 7: อาจารย์ธนกฤต -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=ธนกฤต" alt="อาจารย์ธนกฤต"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">ธนกฤต จำปาทอง</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">ระบบเครือข่าย</p>
                        </div>
                    </div>

                    <!-- Teacher 8: อาจารย์เมธาวี -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=เมธาวี" alt="อาจารย์เมธาวี"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">เมธาวี ภู่โต</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">อิเล็กทรอนิกส์</p>
                        </div>
                    </div>

                    <!-- Teacher 9: อาจารย์อนุชา -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=อนุชา" alt="อาจารย์อนุชา"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">อนุชา ดำรงค์สกุล</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">มัลติมีเดีย</p>
                        </div>
                    </div>

                    <!-- Teacher 10: อาจารย์รพีพร -->
                    <div class="tech-card p-4 text-center">
                        <img src="https://placehold.co/300x300/1F1539/EC4899?text=รพีพร" alt="อาจารย์รพีพร"
                            class="w-32 h-32 object-cover rounded-full mx-auto ring-4 ring-violet-500/50 hover:ring-fuchsia-500 transition duration-300">
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-white">รพีพร ชูสุวรรณ</h3>
                            <p class="text-xs text-violet-400">อาจารย์ประจำแผนก</p>
                            <p class="text-xs text-gray-500 mt-1">ปัญญาประดิษฐ์</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects" class="py-24 bg-gray-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="800">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white">คอรส์เรียน<span class="text-violet-400">สุดล้ำ</span>
                    </h2>
                    <div class="mt-4 h-1 w-24 bg-fuchsia-500 mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Project 1: ระบบจัดการร้านค้าออนไลน์ -->
                    <div class="tech-card p-4 transition duration-300 hover:shadow-xl hover:shadow-fuchsia-500/20">
                        <div class="h-48 rounded-lg overflow-hidden mb-4 relative">
                            <img src="https://placehold.co/600x400/1F1539/A855F7?text=E-Commerce+System"
                                alt="Project 1" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-violet-900/50 flex items-center justify-center text-xs font-mono text-white p-2">
                                WEB APP | PHP & MYSQL
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">ระบบจัดการร้านค้าออนไลน์</h3>
                        <p class="text-gray-400 text-sm mb-3">โครงการพัฒนาระบบจัดการร้านค้าออนไลน์ด้วย PHP และ
                            MySQL
                            สำหรับผู้ประกอบการขนาดเล็ก</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> สมชาย & สมศรี</span>
                            <span><i class="fas fa-calendar-alt mr-1"></i> 2566</span>
                        </div>
                        <a href="http://122.154.173.20/Stock/frontend/login.page.php"
                            class="mt-4 inline-block px-4 py-2 bg-fuchsia-600 text-white rounded-full hover:bg-fuchsia-700 transition duration-300 text-sm">
                            ดูโครงการ <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>

                    <!-- Project 2: ระบบควบคุมอุปกรณ์ไฟฟ้าผ่านแอปพลิเคชัน -->
                    <div class="tech-card p-4 transition duration-300 hover:shadow-xl hover:shadow-fuchsia-500/20">
                        <div class="h-48 rounded-lg overflow-hidden mb-4 relative">
                            <img src="https://placehold.co/600x400/1F1539/A855F7?text=Smart+Home+IOT" alt="Project 2"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-violet-900/50 flex items-center justify-center text-xs font-mono text-white p-2">
                                IOT | Arduino & Mobile App
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">ระบบควบคุมอุปกรณ์ไฟฟ้า</h3>
                        <p class="text-gray-400 text-sm mb-3">
                            โครงการพัฒนาระบบควบคุมอุปกรณ์ไฟฟ้าภายในบ้านผ่านแอปพลิเคชันบนสมาร์ทโฟน ด้วย Arduino และ
                            IoT
                        </p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> วิชัย & มานะ</span>
                            <span><i class="fas fa-calendar-alt mr-1"></i> 2566</span>
                        </div>
                        <button
                            class="mt-4 inline-block px-4 py-2 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition duration-300 text-sm">
                            ดูโครงการ <i class="fas fa-external-link-alt ml-1"></i>
                        </button>
                    </div>

                    <!-- Project 3: แอปพลิเคชันแจ้งเตือนการใช้น้ำ -->
                    <div class="tech-card p-4 transition duration-300 hover:shadow-xl hover:shadow-fuchsia-500/20">
                        <div class="h-48 rounded-lg overflow-hidden mb-4 relative">
                            <img src="https://placehold.co/600x400/1F1539/A855F7?text=Water+Saving+App"
                                alt="Project 3" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-violet-900/50 flex items-center justify-center text-xs font-mono text-white p-2">
                                MOBILE APP | Android & Sensors
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">แอปแจ้งเตือนการใช้น้ำ</h3>
                        <p class="text-gray-400 text-sm mb-3">โครงการพัฒนาแอปพลิเคชันแจ้งเตือนการใช้น้ำในครัวเรือน
                            เพื่อส่งเสริมการประหยัดน้ำและพลังงาน</p>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> สมใจ & สมหมาย</span>
                            <span><i class="fas fa-calendar-alt mr-1"></i> 2565</span>
                        </div>
                        <button
                            class="mt-4 inline-block px-4 py-2 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition duration-300 text-sm">
                            ดูโครงการ <i class="fas fa-external-link-alt ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="text-center mt-16 fade-in-up" data-delay="900">
                    <button
                        class="px-10 py-3 btn-primary text-white rounded-full font-bold shadow-lg shadow-violet-500/30">
                        ดูผลงานทั้งหมด <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="1000">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white">ติดต่อ<span class="text-fuchsia-400">เรา</span></h2>
                    <div class="mt-4 h-1 w-24 bg-violet-500 mx-auto rounded-full"></div>
                    <p class="mt-4 text-gray-400 max-w-2xl mx-auto">
                        หากมีข้อสงสัยหรือต้องการข้อมูลเพิ่มเติม
                        สามารถติดต่อแผนกวิชาเทคโนโลยีคอมพิวเตอร์ได้ตามช่องทางด้านล่าง
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="tech-card p-8 shadow-2xl shadow-violet-500/20">
                        <h3 class="text-2xl font-semibold text-white mb-6">ส่งข้อความดิจิทัล</h3>
                        <form id="contact-form">
                            <div class="mb-4">
                                <label for="name"
                                    class="block text-gray-400 text-sm font-medium mb-2">ชื่อ-นามสกุล</label>
                                <input type="text" id="name" name="name"
                                    class="w-full px-4 py-3 bg-gray-800 border border-violet-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-500 text-white">
                            </div>
                            <div class="mb-4">
                                <label for="email"
                                    class="block text-gray-400 text-sm font-medium mb-2">อีเมล</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-3 bg-gray-800 border border-violet-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-500 text-white">
                            </div>
                            <div class="mb-4">
                                <label for="subject"
                                    class="block text-gray-400 text-sm font-medium mb-2">เรื่อง</label>
                                <input type="text" id="subject" name="subject"
                                    class="w-full px-4 py-3 bg-gray-800 border border-violet-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-500 text-white">
                            </div>
                            <div class="mb-6">
                                <label for="message"
                                    class="block text-gray-400 text-sm font-medium mb-2">ข้อความ</label>
                                <textarea id="message" name="message" rows="4"
                                    class="w-full px-4 py-3 bg-gray-800 border border-violet-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-500 text-white"></textarea>
                            </div>
                            <button type="submit" id="submit-form"
                                class="w-full px-6 py-3 btn-primary text-white rounded-full font-bold transition duration-300">
                                ส่งข้อความ <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-6">
                        <div class="tech-card p-6 flex items-start shadow-xl shadow-fuchsia-500/10">
                            <div
                                class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-violet-600/30 text-fuchsia-400">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-white">ที่อยู่</h4>
                                <p class="mt-1 text-gray-400 text-sm">
                                    แผนกวิชาเทคโนโลยีคอมพิวเตอร์ วิทยาลัยเทคนิคลพบุรี<br>
                                    เลขที่ 323 ถนนนารายณ์มหาราช อำเภอเมือง จังหวัดลพบุรี 15000
                                </p>
                            </div>
                        </div>

                        <div class="tech-card p-6 flex items-start shadow-xl shadow-fuchsia-500/10">
                            <div
                                class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-violet-600/30 text-fuchsia-400">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-white">โทรศัพท์</h4>
                                <p class="mt-1 text-gray-400 text-sm">
                                    036-411-083 ต่อ 123 | 036-422-123
                                </p>
                            </div>
                        </div>

                        <div class="tech-card p-6 flex items-start shadow-xl shadow-fuchsia-500/10">
                            <div
                                class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-violet-600/30 text-fuchsia-400">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-white">อีเมล</h4>
                                <p class="mt-1 text-gray-400 text-sm">
                                    computer.tech@lbtech.ac.th
                                </p>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="mt-8 tech-card p-6">
                            <h4 class="text-lg font-medium text-white mb-4">เชื่อมต่อกับเราในโลกดิจิทัล</h4>
                            <div class="flex space-x-4">
                                <a href="https://www.facebook.com/Softwarelopburi" target="_blank"
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" target="_blank"
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-pink-600 text-white hover:bg-pink-700 transition duration-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" target="_blank"
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 transition duration-300">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://lin.ee/qZgzZ8o" target="_blank"
                                    class="h-10 w-10 flex items-center justify-center rounded-full bg-green-600 text-white hover:bg-green-700 transition duration-300">
                                    <i class="fab fa-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up" data-delay="1100">
                <div class="tech-card rounded-xl overflow-hidden shadow-2xl shadow-violet-500/20">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.7661557562236!2d100.61351731483559!3d14.799376989666869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d78a4a8713171%3A0xf8c7d9a61a593f4d!2z4Lin4Li04LiX4Lii4Liy4Lil4Lix4Lii4LmA4LiX4LiE4LiZ4Li04LiE4Lil4Lie4Lia4Li44Lij4Li1!5e0!3m2!1sth!2sth!4v1645678901234!5m2!1sth!2sth"
                        width="100%" height="450" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-violet-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-400 text-sm">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Column 1: Logo & Info -->
                <div>
                    <a href="#home"
                        class="text-xl font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-500 mb-4 block">
                        Center-Learning <span class="text-white">TECHNOLOGY COMPUTER</span>
                    </a>
                    <p class="mb-4">
                        แผนกวิชาเทคโนโลยีคอมพิวเตอร์ วิทยาลัยเทคนิคลพบุรี
                    </p>
                    <div class="flex space-x-4">
                        <i class="fab fa-facebook-f text-lg hover:text-violet-400 transition"></i>
                        <i class="fab fa-instagram text-lg hover:text-violet-400 transition"></i>
                        <i class="fab fa-youtube text-lg hover:text-violet-400 transition"></i>
                        <i class="fab fa-line text-lg hover:text-violet-400 transition"></i>
                    </div>
                </div>

                <!-- Column 2: ลิงก์ที่เกี่ยวข้อง -->
                <div>
                    <h5 class="text-white font-semibold mb-4 text-base">ลิงก์ที่เกี่ยวข้อง</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-violet-400 transition">วิทยาลัยเทคนิคลพบุรี</a>
                        </li>
                        <li><a href="#"
                                class="hover:text-violet-400 transition">สำนักงานคณะกรรมการการอาชีวศึกษา</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition">ระบบบริหารจัดการศึกษา</a>
                        </li>
                        <li><a href="#" class="hover:text-violet-400 transition">ระบบสารสนเทศนักศึกษา</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: แผนกวิชา -->
                <div>
                    <h5 class="text-white font-semibold mb-4 text-base">แผนกวิชา</h5>
                    <ul class="space-y-2">
                        <li><a href="#courses" class="hover:text-violet-400 transition">หลักสูตรที่เปิดสอน</a>
                        </li>
                        <li><a href="#teachers" class="hover:text-violet-400 transition">คณาจารย์</a></li>
                        <li><a href="#projects" class="hover:text-violet-400 transition">ผลงานนักศึกษา</a></li>
                        <li><a href="#about" class="hover:text-violet-400 transition">ประวัติความเป็นมา</a></li>
                    </ul>
                </div>

                <!-- Column 4: ติดต่อเรา -->
                <div>
                    <h5 class="text-white font-semibold mb-4 text-base">ติดต่อเรา</h5>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-gray-400"></i>
                            <span>036-411-083 ต่อ 123</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-gray-400"></i>
                            <span>computer.tech@lbtech.ac.th</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-gray-400"></i>
                            <span>ลพบุรี, ประเทศไทย 15000</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-violet-900 text-center">
                <p>&copy; 2025 แผนกวิชาเทคโนโลยีคอมพิวเตอร์ วิทยาลัยเทคนิคลพบุรี. สงวนลิขสิทธิ์.</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript สำหรับการสร้าง Particle Background และ Scroll Animation

        // 1. Particle Background Generation
        function createParticleBackground() {
            const container = document.getElementById('particle-bg');
            const numParticles = 30; // จำนวนอนุภาค

            for (let i = 0; i < numParticles; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                const size = Math.random() * 6 + 4;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;

                const startX = Math.random() * window.innerWidth;
                const startY = Math.random() * (document.body.offsetHeight * 0.9);
                particle.style.left = `${startX}px`;
                particle.style.top = `${startY}px`;

                const duration = Math.random() * 20 + 10;
                particle.style.animationDuration = `${duration}s`;

                const destX = (Math.random() - 0.5) * window.innerWidth * 0.5;
                const destY = (Math.random() - 0.5) * window.innerHeight * 0.5;
                particle.style.setProperty('--x', `${destX}px`);
                particle.style.setProperty('--y', `${destY}px`);

                particle.style.animationDelay = `-${Math.random() * 10}s`;

                container.appendChild(particle);
            }
        }

        // 2. Scroll-Triggered Fade In Up Animation (Intersection Observer)
        function setupScrollAnimations() {
            const elements = document.querySelectorAll('.fade-in-up');

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = parseInt(entry.target.getAttribute('data-delay') || '0', 10);
                        setTimeout(() => {
                            entry.target.classList.add('visible');
                        }, delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px',
                threshold: 0.1
            });

            elements.forEach(el => {
                observer.observe(el);
            });
        }

        // 3. Hologram Visual Animation (CSS Keyframes)
        function setupHologramAnimation() {
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin-slow {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                @keyframes spin-reverse-slow {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(-360deg); }
                }
                .animate-spin-slow {
                    animation: spin-slow 40s linear infinite;
                }
                .animate-spin-reverse-slow {
                    animation: spin-reverse-slow 30s linear infinite;
                }
            `;
            document.head.appendChild(style);
        }

        // 4. Mobile Menu Toggle & Smooth Scrolling
        function setupNavigation() {
            // Mobile Menu Toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            });

            // Smooth Scrolling for Anchor Links & close mobile menu
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Offset for fixed navbar (h-20 = 80px)
                        const offset = 80;
                        window.scrollTo({
                            top: targetElement.offsetTop - offset,
                            behavior: 'smooth'
                        });

                        // Close mobile menu if open
                        const mobileMenu = document.getElementById('mobile-menu');
                        if (!mobileMenu.classList.contains('hidden') && this.classList.contains(
                                'mobile-nav-link')) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            });
        }

        // 5. Course Details Pop-ups (ใช้ SweetAlert2 แทน alert())
        function setupCourseDetails() {
            document.querySelectorAll('.course-details').forEach(button => {
                button.addEventListener('click', function() {
                    const course = this.getAttribute('data-course');
                    let title, content;

                    switch (course) {
                        case 'ปวช':
                            title = 'รายละเอียดหลักสูตร ปวช. สาขาเทคโนโลยีคอมพิวเตอร์';
                            content = `
                                <p class="mb-3 text-gray-700">หลักสูตรประกาศนียบัตรวิชาชีพ มุ่งเน้นผลิตนักศึกษาที่มีความรู้ความสามารถด้านคอมพิวเตอร์ทั้งฮาร์ดแวร์และซอฟต์แวร์</p>
                                <p class="mb-3 text-gray-700"><strong>ระยะเวลาการศึกษา:</strong> 3 ปี</p>
                                <p class="mb-3 text-gray-700"><strong>คุณสมบัติผู้เข้าศึกษา:</strong> จบ ม.3 หรือเทียบเท่า</p>
                                <p class="mb-3 text-gray-700"><strong>แนวทางการประกอบอาชีพ:</strong> ช่างซ่อมบำรุง, ผู้ช่วยโปรแกรมเมอร์, ผู้ช่วยดูแลระบบเครือข่าย</p>
                            `;
                            break;
                        case 'ปวส-ฮาร์ดแวร์':
                            title = 'รายละเอียดหลักสูตร ปวส. สาขาคอมพิวเตอร์ฮาร์ดแวร์';
                            content = `
                                <p class="mb-3 text-gray-700">หลักสูตรประกาศนียบัตรวิชาชีพชั้นสูง มุ่งเน้นผลิตนักศึกษาที่มีความเชี่ยวชาญด้านฮาร์ดแวร์คอมพิวเตอร์ ระบบเครือข่ายขั้นสูง และ IoT</p>
                                <p class="mb-3 text-gray-700"><strong>ระยะเวลาการศึกษา:</strong> 2 ปี</p>
                                <p class="mb-3 text-gray-700"><strong>คุณสมบัติผู้เข้าศึกษา:</strong> จบ ปวช. หรือ ม.6</p>
                                <p class="mb-3 text-gray-700"><strong>แนวทางการประกอบอาชีพ:</strong> ช่างเทคนิคคอมพิวเตอร์, ผู้ดูแลระบบเครือข่าย, ช่างเทคนิคด้านอิเล็กทรอนิกส์</p>
                            `;
                            break;
                        case 'ปวส-ซอฟต์แวร์':
                            title = 'รายละเอียดหลักสูตร ปวส. สาขาคอมพิวเตอร์ซอฟต์แวร์';
                            content = `
                                <p class="mb-3 text-gray-700">หลักสูตรประกาศนียบัตรวิชาชีพชั้นสูง มุ่งเน้นผลิตนักพัฒนาซอฟต์แวร์ เว็บไซต์ และแอปพลิเคชันมือถือ ระบบฐานข้อมูล</p>
                                <p class="mb-3 text-gray-700"><strong>ระยะเวลาการศึกษา:</strong> 2 ปี</p>
                                <p class="mb-3 text-gray-700"><strong>คุณสมบัติผู้เข้าศึกษา:</strong> จบ ปวช. หรือ ม.6</p>
                                <p class="mb-3 text-gray-700"><strong>แนวทางการประกอบอาชีพ:</strong> นักพัฒนาซอฟต์แวร์, นักพัฒนาเว็บไซต์, นักพัฒนาแอปพลิเคชันมือถือ</p>
                            `;
                            break;
                    }

                    // แสดง SweetAlert2
                    Swal.fire({
                        title: title,
                        html: `<div class="text-left">${content}</div>`, // ครอบด้วย div เพื่อจัดข้อความให้อ่านง่าย
                        width: 700,
                        confirmButtonText: 'ปิด',
                        confirmButtonColor: '#EC4899', // ใช้สี Fuchsia
                        customClass: {
                            popup: 'bg-white text-gray-900 rounded-xl shadow-2xl', // ปรับสไตล์ของ pop-up
                            title: 'text-2xl font-bold text-gray-900',
                        }
                    });
                });
            });
        }

        // 6. Contact Form Submission (ใช้ SweetAlert2 แทน alert())
        function setupContactForm() {
            document.getElementById('contact-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form values
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;

                // Validate form
                if (!name || !email || !subject || !message) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'กรุณากรอกข้อมูลให้ครบถ้วนเพื่อส่งข้อความ',
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#EC4899'
                    });
                    return;
                }

                // Simulate form submission
                Swal.fire({
                    title: 'กำลังส่งข้อความ...',
                    text: 'กรุณารอสักครู่ ระบบดิจิทัลกำลังประมวลผล',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulate API call delay
                setTimeout(() => {
                    Swal.fire({
                        title: 'ส่งข้อความสำเร็จ!',
                        text: 'ขอบคุณที่ติดต่อเรา เราจะตอบกลับทางอีเมลโดยเร็วที่สุด',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#EC4899'
                    });

                    // Reset form
                    document.getElementById('contact-form').reset();
                }, 2000);
            });
        }

        // 7. Highlight active navigation link based on scroll position
        function setupScrollHighlight() {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const currentSection = entry.target.getAttribute('id');

                        navLinks.forEach(link => {
                            link.classList.remove('text-violet-400', 'bg-violet-900/50');
                            link.classList.add('text-gray-300');

                            if (link.getAttribute('href') === `#${currentSection}`) {
                                link.classList.remove('text-gray-300');
                                link.classList.add('text-violet-400');
                                // สำหรับ mobile menu ให้เพิ่มพื้นหลังด้วย
                                if (link.classList.contains('mobile-nav-link')) {
                                    link.classList.add('bg-violet-900/50');
                                }
                            }
                        });
                    }
                });
            }, {
                rootMargin: '-30% 0px -50% 0px', // ทำให้ลิงก์เปลี่ยนเมื่อส่วนนั้นอยู่ช่วงกลางหน้าจอ
                threshold: 0 // ไม่ต้องการ threshold เพราะ rootMargin จัดการแล้ว
            });

            sections.forEach(section => {
                observer.observe(section);
            });
        }


        // Run all setup functions when the window loads
        window.onload = function() {
            createParticleBackground();
            setupScrollAnimations();
            setupHologramAnimation();
            setupNavigation();
            setupCourseDetails();
            setupContactForm();
            setupScrollHighlight(); // เปิดใช้งานการไฮไลต์เมื่อ scroll
        };
    </script>
</body>

</html>
