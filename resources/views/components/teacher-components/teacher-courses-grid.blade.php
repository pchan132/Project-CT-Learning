<!-- ส่วนคอร์สเรียนของฉัน: Grid Layout พร้อมปุ่มดำเนินการ -->
<section>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">คอร์สของฉัน</h2>
    @if ($courses->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    @if ($course->cover_image_url)
                        <img src="{{ asset('storage/' . $course->cover_image_url) }}" class="w-full h-40 object-cover"
                            alt="{{ $course->title }}">
                    @else
                        <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                        <a href="{{ route('teacher.courses.show', $course) }}"
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            จัดการคอร์ส
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-book-open text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">คุณยังไม่มีคอร์สเรียน</h3>
            <p class="text-gray-500 mb-4">เริ่มต้นโดยการสร้างคอร์สแรกของคุณ</p>
            <a href="{{ route('teacher.courses.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>สร้างคอร์สใหม่
            </a>
        </div>
    @endif
</section>
