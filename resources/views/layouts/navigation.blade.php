<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::check() ? route('dashboard') : route('welcome') }}"
                        class="flex items-center space-x-2">
                        {{-- <div
                            class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center"> --}}
                        {{-- <i class="fas fa-graduation-cap text-white text-sm"></i> --}}
                        <img src="{{ asset('./storage/imgs/logo-technology.png') }}" alt="Logo"
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        {{-- </div> --}}
                        <span class="font-bold text-lg text-gray-900 dark:text-white hidden sm:block">
                            Computer
                            Technology Learning</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                    <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex">
                        <!-- Admin Navigation -->
                        @if (auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="flex items-center space-x-2">
                                <i class="fas fa-tachometer-alt text-sm"></i>
                                <span>{{ __('Dashboard') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" class="flex items-center space-x-2">
                                <i class="fas fa-users text-sm"></i>
                                <span>{{ __('ผู้ใช้') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.courses')" :active="request()->routeIs('admin.courses*')" class="flex items-center space-x-2">
                                <i class="fas fa-book text-sm"></i>
                                <span>{{ __('รายวิชาออนไลน์') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('admin.certificate-templates.index')" :active="request()->routeIs('admin.certificate-templates*')" class="flex items-center space-x-2">
                                <i class="fas fa-book text-sm"></i>
                                <span>{{ __('แม่แบบใบประกาศนียบัตร') }}</span>
                            </x-nav-link>
                            {{-- <x-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')" class="flex items-center space-x-2">
                                <i class="fas fa-chart-bar text-sm"></i>
                                <span>{{ __('สถิติ') }}</span>
                            </x-nav-link> --}}
                        @endif

                        <!-- Teacher Navigation -->
                        @if (auth()->user()->isTeacher())
                            <x-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')" class="flex items-center space-x-2">
                                <i class="fas fa-home text-sm"></i>
                                <span>{{ __('หน้าหลัก') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*')" class="flex items-center space-x-2">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                                <span>{{ __('รายวิชาของฉัน') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" class="flex items-center space-x-2">
                                <i class="fas fa-users text-sm"></i>
                                <span>{{ __('ผู้สอนทั้งหมด') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('teacher.signature.index')" :active="request()->routeIs('teacher.signature.*')" class="flex items-center space-x-2">
                                <i class="fas fa-signature text-sm"></i>
                                <span>{{ __('ลายเซ็นใบประกาศนียบัตร') }}</span>
                            </x-nav-link>

                            <x-nav-link :href="route('teacher.profile.edit')" :active="request()->routeIs('teacher.profile.edit')" class="flex items-center space-x-2">
                                <i class="fas fa-user-edit text-sm"></i>
                                <span>{{ __('แก้ไขโปรไฟล์') }}</span>
                            </x-nav-link>
                        @endif

                        <!-- Student Navigation -->
                        @if (auth()->user()->isStudent())
                            <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" class="flex items-center space-x-2">
                                <i class="fas fa-home text-sm"></i>
                                <span>{{ __('หน้าหลัก') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('student.courses.my-courses')" :active="request()->routeIs('student.courses.my-courses')" class="flex items-center space-x-2">
                                <i class="fas fa-book-reader text-sm"></i>
                                <span>{{ __('รายวิชาของฉัน') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('student.courses.index')" :active="request()->routeIs('student.courses.index')" class="flex items-center space-x-2">
                                <i class="fas fa-search text-sm"></i>
                                <span>{{ __('ค้นหารายวิชา') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" class="flex items-center space-x-2">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                                <span>{{ __('ผู้สอน') }}</span>
                            </x-nav-link>
                            <x-nav-link :href="route('student.certificates.index')" :active="request()->routeIs('student.certificates.*')" class="flex items-center space-x-2">
                                <i class="fas fa-certificate text-sm"></i>
                                <span>{{ __('ใบประกาศ') }}</span>
                            </x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            <div class="flex items-center space-x-3">
                <!-- Dark Mode Toggle -->
                <button id="mode-toggle" type="button"
                    class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <svg id="sun-icon" class="mode-toggle-icon w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg id="moon-icon" class="mode-toggle-icon w-5 h-5 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                @auth
                    <!-- User Role Badge (Desktop) -->
                    <div class="hidden sm:flex items-center">
                        @if (auth()->user()->isAdmin())
                            <span
                                class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-full">Admin</span>
                        @elseif (auth()->user()->isTeacher())
                            <span
                                class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full">Teacher</span>
                        @else
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full">Student</span>
                        @endif
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                                    {{-- <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div> --}}
                                    <div class="hidden md:block text-left">
                                        <div class="font-medium">{{ Auth::user()->name }}</div>
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                    <i class="fas fa-user-cog mr-2 text-gray-400"></i>
                                    {{ __('ตั้งค่าโปรไฟล์') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center text-red-600 dark:text-red-400">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        {{ __('ออกจากระบบ') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth

                @guest
                    <!-- Guest Navigation Buttons (Desktop) -->
                    <div class="hidden sm:flex items-center space-x-2">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                            เข้าสู่ระบบ
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                ลงทะเบียน
                            </a>
                        @endif
                    </div>
                @endguest

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">

        @guest
            <!-- Guest Mobile Menu -->
            <div class="pt-4 pb-4 space-y-2 px-4">
                <a href="{{ route('login') }}"
                    class="block w-full px-4 py-3 text-center text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>เข้าสู่ระบบ
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="block w-full px-4 py-3 text-center text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 font-medium transition-all duration-200 shadow-md">
                        <i class="fas fa-user-plus mr-2"></i>ลงทะเบียน
                    </a>
                @endif
            </div>
        @endguest

        @auth
            <div class="pt-2 pb-3 space-y-1 px-4">
                <!-- Admin Navigation -->
                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="flex items-center">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>{{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')" class="flex items-center">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>{{ __('จัดการผู้ใช้') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.courses')" :active="request()->routeIs('admin.courses*')" class="flex items-center">
                        <i class="fas fa-book mr-3 w-5 text-center"></i>{{ __('จัดการรายวิชาออนไลน์') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')" class="flex items-center">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>{{ __('สถิติระบบ') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Teacher Navigation -->
                @if (auth()->user()->isTeacher())
                    <x-responsive-nav-link :href="route('teacher.dashboard')" :active="request()->routeIs('teacher.dashboard')" class="flex items-center">
                        <i class="fas fa-home mr-3 w-5 text-center"></i>{{ __('หน้าหลัก') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teacher.courses.index')" :active="request()->routeIs('teacher.courses.*')" class="flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>{{ __('รายวิชาของฉัน') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" class="flex items-center">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>{{ __('ผู้สอนทั้งหมด') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teacher.signature.index')" :active="request()->routeIs('teacher.signature.*')" class="flex items-center">
                        <i class="fas fa-signature mr-3 w-5 text-center"></i>{{ __('ลายเซ็นใบประกาศนียบัตร') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teacher.profile.edit')" :active="request()->routeIs('teacher.profile.edit')" class="flex items-center space-x-2">
                        <i class="fas fa-user-edit text-sm"></i>
                        <span>{{ __('แก้ไขโปรไฟล์') }}</span>
                    </x-responsive-nav-link>
                @endif

                <!-- Student Navigation -->
                @if (auth()->user()->isStudent())
                    <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" class="flex items-center">
                        <i class="fas fa-home mr-3 w-5 text-center"></i>{{ __('หน้าหลัก') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('student.courses.my-courses')" :active="request()->routeIs('student.courses.my-courses')" class="flex items-center">
                        <i class="fas fa-book-reader mr-3 w-5 text-center"></i>{{ __('รายวิชาของฉัน') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('student.courses.index')" :active="request()->routeIs('student.courses.index')" class="flex items-center">
                        <i class="fas fa-search mr-3 w-5 text-center"></i>{{ __('ค้นหารายวิชา') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teachers.index')" :active="request()->routeIs('teachers.*')" class="flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i>{{ __('ผู้สอน') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('student.certificates.index')" :active="request()->routeIs('student.certificates.*')" class="flex items-center">
                        <i class="fas fa-certificate mr-3 w-5 text-center"></i>{{ __('ใบประกาศ') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-600">
                <div class="flex items-center px-4 mb-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1 px-4">
                    <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                        <i class="fas fa-user-cog mr-3 w-5 text-center"></i>{{ __('ตั้งค่าโปรไฟล์') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center text-red-600 dark:text-red-400">
                            <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>{{ __('ออกจากระบบ') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
