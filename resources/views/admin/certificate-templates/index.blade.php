@extends('layouts.app')

@section('title', 'จัดการ Certificate Templates')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    📜 จัดการ Certificate Templates
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">ออกแบบและจัดการ Template ใบประกาศนียบัตร</p>
            </div>
            <a href="{{ route('admin.certificate-templates.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                สร้าง Template ใหม่
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-green-700 dark:text-green-300">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-red-700 dark:text-red-300">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($templates->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">📜</div>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">ยังไม่มี Template</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">สร้าง Template ใหม่เพื่อใช้กับใบประกาศนียบัตร</p>
                <a href="{{ route('admin.certificate-templates.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    สร้าง Template แรก
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($templates as $template)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow
                            {{ $template->is_active ? 'ring-2 ring-green-500' : '' }}">
                        <!-- Template Preview -->
                        <div class="aspect-[16/9] relative overflow-hidden"
                            style="background: linear-gradient(135deg, {{ $template->primary_color }}22, {{ $template->border_color }}22);">

                            @if ($template->background_image)
                                <img src="{{ $template->background_image_url }}"
                                    class="absolute inset-0 w-full h-full object-cover opacity-30" alt="">
                            @endif

                            <!-- Mock Certificate -->
                            <div class="absolute inset-4 bg-white rounded-lg shadow-lg flex flex-col items-center justify-center p-4"
                                style="border: 4px solid {{ $template->border_color }};">
                                @if ($template->logo_image)
                                    <img src="{{ $template->logo_image_url }}" class="w-12 h-12 object-contain mb-2"
                                        alt="">
                                @else
                                    <div class="text-2xl mb-2">🏆</div>
                                @endif
                                <div class="text-xs font-bold" style="color: {{ $template->primary_color }};">CERTIFICATE
                                </div>
                                <div class="h-1 w-16 rounded mt-2" style="background: {{ $template->text_color }};"></div>

                                <!-- Signature Preview -->
                                <div class="flex justify-between w-full mt-4 px-2">
                                    <div class="text-center">
                                        @if ($template->admin_signature)
                                            <div class="w-8 h-4 bg-gray-200 rounded"></div>
                                        @endif
                                        <div class="text-[6px] text-gray-400">Admin</div>
                                    </div>
                                    @if ($template->show_teacher_signature)
                                        <div class="text-center">
                                            <div class="w-8 h-4 bg-gray-200 rounded"></div>
                                            <div class="text-[6px] text-gray-400">Teacher</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Active Badge -->
                            @if ($template->is_active)
                                <div
                                    class="absolute top-2 right-2 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">
                                    ✓ ใช้งานอยู่
                                </div>
                            @endif
                        </div>

                        <!-- Template Info -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-1">
                                {{ $template->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $template->description ?? 'ไม่มีคำอธิบาย' }}
                            </p>

                            <!-- Color Swatches -->
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-full border-2 border-white shadow"
                                    style="background: {{ $template->primary_color }};" title="สีหลัก"></div>
                                <div class="w-6 h-6 rounded-full border-2 border-white shadow"
                                    style="background: {{ $template->border_color }};" title="สีกรอบ"></div>
                                <div class="w-6 h-6 rounded-full border-2 border-white shadow"
                                    style="background: {{ $template->text_color }};" title="สีตัวอักษร"></div>

                                <span class="ml-auto text-xs text-gray-400">
                                    สร้างโดย {{ $template->creator->name }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 flex-wrap">
                                @unless ($template->is_active)
                                    <form action="{{ route('admin.certificate-templates.set-active', $template) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm font-medium rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                            ตั้งเป็นหลัก
                                        </button>
                                    </form>
                                @endunless

                                <a href="{{ route('admin.certificate-templates.preview', $template) }}" target="_blank"
                                    class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                    ดูตัวอย่าง
                                </a>

                                <a href="{{ route('admin.certificate-templates.edit', $template) }}"
                                    class="px-3 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 text-sm font-medium rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-colors">
                                    แก้ไข
                                </a>

                                @unless ($template->is_active)
                                    <form action="{{ route('admin.certificate-templates.destroy', $template) }}" method="POST"
                                        onsubmit="return confirm('ต้องการลบ Template นี้หรือไม่?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                            ลบ
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
