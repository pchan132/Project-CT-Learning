@extends('layouts.app')

@section('title', 'ตัวอย่างใบประกาศนียบัตร - ' . $template->name)

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@600;700&family=Sarabun:wght@300;400;600;700&display=swap"
        rel="stylesheet">
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                <i class="fas fa-certificate text-yellow-500 mr-3"></i>
                ตัวอย่างใบประกาศนียบัตร
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                ดูตัวอย่าง Template: <span class="font-semibold">{{ $template->name }}</span>
                @if ($template->is_active)
                    <span
                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check mr-1"></i>Active
                    </span>
                @endif
            </p>
        </div>

        <!-- Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <i class="fas fa-cog text-blue-500 mr-2"></i>ปรับแต่งตัวอย่าง
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ชื่อนักเรียน (ตัวอย่าง)
                    </label>
                    <input type="text" id="student-name" value="นักเรียน ตัวอย่าง" oninput="updateStudentName()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        เลือกคอร์ส
                    </label>
                    <select id="course-select" onchange="updateCoursePreview()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="" data-title="คอร์สตัวอย่าง">-- คอร์สตัวอย่าง --</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" data-title="{{ $course->title }}">
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        เลือกครูผู้สอน (ลายเซ็น)
                    </label>
                    <select id="teacher-select" onchange="updateTeacherPreview()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="" data-name="อาจารย์ ตัวอย่าง" data-signature="">-- ครูตัวอย่าง --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" data-name="{{ $teacher->name }}"
                                data-signature="{{ $teacher->signature_image ? asset('storage/' . $teacher->signature_image) : '' }}">
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="downloadPreviewPDF()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>ดาวน์โหลด PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Template Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>ข้อมูล Template
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">ผู้ลงนาม:</span>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $template->admin_name ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">ตำแหน่ง:</span>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $template->admin_position ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">ตำแหน่งลายเซ็นผู้บริหาร:</span>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ $template->admin_signature_position === 'left' ? 'ซ้าย' : 'ขวา' }}</p>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">แสดงลายเซ็นครู:</span>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ $template->show_teacher_signature ? 'แสดง' : 'ซ่อน' }}</p>
                </div>
            </div>
        </div>

        <!-- Certificate Preview -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <i class="fas fa-eye text-purple-500 mr-2"></i>ตัวอย่างใบประกาศนียบัตร
            </h3>

            <div class="overflow-x-auto pb-4">
                @php
                    $firstCourse = $courses->first();
                    $firstTeacher = $teachers->first();
                @endphp

                <x-certificate-preview :template="$template" :studentName="'นักเรียน ตัวอย่าง'" :courseName="$firstCourse ? $firstCourse->title : 'คอร์สตัวอย่าง'" :teacherName="$firstTeacher ? $firstTeacher->name : 'อาจารย์ ตัวอย่าง'"
                    :teacherSignature="$firstTeacher && $firstTeacher->signature_image
                        ? asset('storage/' . $firstTeacher->signature_image)
                        : null" containerId="certificate-preview" />
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                นี่คือตัวอย่างว่าใบประกาศนียบัตรจะแสดงอย่างไรเมื่อนักเรียนจบคอร์ส
            </p>
        </div>

        <!-- Back Button -->
        <div class="mt-6 flex gap-4">
            <a href="{{ route('admin.certificate-templates.edit', $template) }}"
                class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                <i class="fas fa-edit mr-2"></i>แก้ไข Template
            </a>
            <a href="{{ route('admin.certificate-templates.index') }}"
                class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปรายการ Template
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function updateStudentName() {
            const name = document.getElementById('student-name').value || 'นักเรียน ตัวอย่าง';
            const nameEl = document.querySelector('#certificate-preview h1');
            if (nameEl) {
                nameEl.textContent = name;
            }
        }

        function updateCoursePreview() {
            const select = document.getElementById('course-select');
            const selectedOption = select.options[select.selectedIndex];
            const courseTitle = selectedOption.dataset.title || 'คอร์สตัวอย่าง';

            const courseTitleEl = document.querySelector('#certificate-preview h2');
            if (courseTitleEl) {
                courseTitleEl.textContent = `"${courseTitle}"`;
            }
        }

        function updateTeacherPreview() {
            const select = document.getElementById('teacher-select');
            const selectedOption = select.options[select.selectedIndex];
            const teacherName = selectedOption.dataset.name || 'อาจารย์ ตัวอย่าง';
            const teacherSignature = selectedOption.dataset.signature || '';

            // หาตำแหน่งลายเซ็นครู (left หรือ right)
            const teacherPosition = '{{ $template->teacher_signature_position ?? 'left' }}';
            const showTeacherSig = {{ $template->show_teacher_signature ? 'true' : 'false' }};

            if (!showTeacherSig) return;

            // หา element ลายเซ็นครูตามตำแหน่ง
            const sigContainers = document.querySelectorAll('#certificate-preview .w-64');
            let teacherSigContainer = null;

            if (teacherPosition === 'left' && sigContainers[0]) {
                teacherSigContainer = sigContainers[0];
            } else if (teacherPosition === 'right' && sigContainers[1]) {
                teacherSigContainer = sigContainers[1];
            }

            if (teacherSigContainer) {
                // อัพเดทชื่อครู
                const nameEl = teacherSigContainer.querySelector('.text-lg.font-bold');
                if (nameEl) {
                    nameEl.textContent = teacherName;
                }

                // อัพเดทลายเซ็น
                const sigBox = teacherSigContainer.querySelector('.h-12');
                if (sigBox) {
                    if (teacherSignature) {
                        sigBox.innerHTML = `<img src="${teacherSignature}" alt="Signature" class="h-10 object-contain">`;
                    } else {
                        sigBox.innerHTML = `<span class="text-2xl text-blue-900 italic">${teacherName}</span>`;
                    }
                }
            }
        }

        async function downloadPreviewPDF() {
            const element = document.getElementById('certificate-preview');
            if (!element) {
                alert('ไม่พบ Certificate Preview');
                return;
            }

            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>กำลังสร้าง...';
            btn.disabled = true;

            try {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: 1123,
                    height: 794,
                });

                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });

                const imgData = canvas.toDataURL('image/png', 1.0);
                pdf.addImage(imgData, 'PNG', 0, 0, 297, 210);

                const studentName = document.getElementById('student-name').value || 'sample';
                pdf.save(`certificate-preview-${studentName}.pdf`);
            } catch (error) {
                console.error('PDF generation failed:', error);
                alert('ไม่สามารถสร้าง PDF ได้');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
@endpush
