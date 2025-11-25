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

    <!-- TinyMCE with PowerPaste Plugin -->
    <script src="https://cdn.tiny.cloud/1/qagffr3pkuv17a8onygc0nh1ic02c4vqvj3sxxbfz0ewj6qp/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

    <!-- SortableJS for Drag and Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <!-- TinyMCE Global Configuration -->
    <script>
        window.tinymceConfig = {
            height: 500,
            menubar: 'file edit view insert format tools table',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount',
                'paste', 'importcss', 'directionality', 'emoticons', 'template',
                'codesample', 'pagebreak', 'nonbreaking', 'quickbars'
            ],
            toolbar: 'undo redo | styles | bold italic underline strikethrough | ' +
                'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | table tabledelete | ' +
                'link image media | removeformat code fullscreen | help',
            toolbar_mode: 'sliding',
            paste_data_images: true,
            paste_as_text: false,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li,table,tr,td,th,span,div",
            paste_retain_style_properties: "color font-size background-color",
            paste_merge_formats: true,
            paste_remove_styles_if_webkit: false,
            automatic_uploads: true,
            images_upload_url: '/upload-image',
            file_picker_types: 'image',
            content_style: `
                body { 
                    font-family: 'Sarabun', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
                    font-size: 16px;
                    line-height: 1.6;
                    padding: 20px;
                }
                @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
            `,
            style_formats: [{
                    title: 'Heading 1',
                    format: 'h1'
                },
                {
                    title: 'Heading 2',
                    format: 'h2'
                },
                {
                    title: 'Heading 3',
                    format: 'h3'
                },
                {
                    title: 'Heading 4',
                    format: 'h4'
                },
                {
                    title: 'Heading 5',
                    format: 'h5'
                },
                {
                    title: 'Heading 6',
                    format: 'h6'
                },
                {
                    title: 'Paragraph',
                    format: 'p'
                },
                {
                    title: 'Blockquote',
                    format: 'blockquote'
                },
                {
                    title: 'Code',
                    format: 'code'
                },
                {
                    title: 'Preformatted',
                    format: 'pre'
                }
            ],
            font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
            fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
            block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
            importcss_append: true,
            template_cdate_format: '[Date Created (CDATE): %d/%m/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %d/%m/%Y : %H:%M:%S]',
            image_advtab: true,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
            quickbars_insert_toolbar: 'quickimage quicktable',
            contextmenu: 'link image table',
            branding: false,
            promotion: false,
            resize: true,
            statusbar: true,
            elementpath: true,
            setup: function(editor) {
                // Add Word Import Button
                editor.ui.registry.addButton('wordimport', {
                    text: 'นำเข้าจาก Word',
                    icon: 'paste-text',
                    tooltip: 'วางเนื้อหาจาก Microsoft Word',
                    onAction: function() {
                        const content = prompt('กรุณาคัดลอกเนื้อหาจาก Word และวางที่นี่:');
                        if (content) {
                            editor.insertContent(content);
                        }
                    }
                });

                editor.on('PastePreProcess', function(e) {
                    // อนุญาตให้วาง HTML จาก Word โดยตรง
                    if (e.content.indexOf('mso-') !== -1 || e.content.indexOf('class="Mso') !== -1) {
                        // Word content detected
                        console.log('Word content detected - processing...');
                    }
                });
            }
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
