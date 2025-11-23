<x-app-layout>
    <!-- content -->
    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-200 font-sans ">
        <!-- ส่วนสถิติ: "ข้อมูลภาพรวม" ที่ใช้การออกแบบแบบ Glass/Sleek Card -->
        @include('components.teacher-components.statistics-teacher-courses')


        <!-- ส่วนคอร์สเรียนของฉัน: Grid Layout พร้อมปุ่มดำเนินการ -->
        @include('components.teacher-components.teacher-courses-grid', ['courses' => $courses])

    </div>

</x-app-layout>
