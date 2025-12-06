@extends('layouts.app')

@section('title', 'ตัวอย่างใบประกาศนียบัตร')

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
                ดูตัวอย่างว่าลายเซ็นของคุณจะปรากฏบนใบประกาศนียบัตรของนักเรียนอย่างไร
            </p>
        </div>

        @if (!$teacher->signature_image)
            <div
                class="mb-6 bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                คุณยังไม่ได้อัพโหลดลายเซ็น
                <a href="{{ route('teacher.profile.edit') }}"
                    class="underline font-semibold">คลิกที่นี่เพื่ออัพโหลดลายเซ็น</a>
            </div>
        @endif

        <!-- Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                <i class="fas fa-cog text-blue-500 mr-2"></i>ปรับแต่งตัวอย่าง
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        เลือกคอร์ส
                    </label>
                    <select id="course-select" onchange="updateCoursePreview()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" data-title="{{ $course->title }}">
                                {{ $course->title }}
                            </option>
                        @endforeach
                        @if ($courses->isEmpty())
                            <option value="">คอร์สตัวอย่าง</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ชื่อนักเรียน (ตัวอย่าง)
                    </label>
                    <input type="text" id="student-name" value="นักเรียน ตัวอย่าง" oninput="updateStudentName()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="downloadPreviewPDF()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>ดาวน์โหลด PDF ตัวอย่าง
                    </button>
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
                    $template = \App\Models\CertificateTemplate::getActiveTemplate();
                    $firstCourse = $courses->first();
                @endphp

                <x-certificate-preview :template="$template" :studentName="'นักเรียน ตัวอย่าง'" :courseName="$firstCourse ? $firstCourse->title : 'คอร์สตัวอย่าง'" :teacherName="$teacher->name"
                    :teacherSignature="$teacher->signature_image ? asset('storage/' . $teacher->signature_image) : null" containerId="certificate-preview" />
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                นี่คือตัวอย่างว่าลายเซ็นของคุณจะแสดงบนใบประกาศนียบัตรของนักเรียนอย่างไร
            </p>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('teacher.profile.edit') }}"
                class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าแก้ไขโปรไฟล์
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function updateCoursePreview() {
            const select = document.getElementById('course-select');
            const selectedOption = select.options[select.selectedIndex];
            const courseTitle = selectedOption.dataset.title || selectedOption.textContent;

            const courseTitleEl = document.querySelector('#certificate-preview h2');
            if (courseTitleEl) {
                courseTitleEl.textContent = `"${courseTitle}"`;
            }
        }

        function updateStudentName() {
            const name = document.getElementById('student-name').value || 'นักเรียน ตัวอย่าง';
            const nameEl = document.querySelector('#certificate-preview h1');
            if (nameEl) {
                nameEl.textContent = name;
            }
        }

        async function downloadPreviewPDF() {
            const element = document.getElementById('certificate-preview');
            if (!element) {
                alert('ไม่พบ Certificate Preview');
                return;
            }

            const btn = event.target;
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
                pdf.save('certificate-preview-teacher.pdf');
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
