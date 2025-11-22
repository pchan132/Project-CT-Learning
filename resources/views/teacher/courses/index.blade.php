<x-app-layout>
    <div class="max-w-5xl mx-auto py-10">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Courses</h1>
            <a href="{{ route('teacher.courses.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                + Create Course
            </a>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="py-3">Cover</th>
                        <th class="py-3">Title</th>
                        <th class="py-3">Description</th>
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $course)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">
                                @if ($course->cover_image_url)
                                    <img src="{{ asset('storage/' . $course->cover_image_url) }}"
                                        class="w-20 h-12 object-cover rounded" alt="{{ $course->title }}">
                                @else
                                    <div class="w-20 h-12 bg-gray-200 rounded"></div>
                                @endif
                            </td>

                            <td class="py-3 font-medium">{{ $course->title }}</td>
                            <td class="py-3 text-gray-600">{{ Str::limit($course->description, 50) }}</td>

                            <td class="py-3">
                                <a href="{{ route('teacher.courses.edit', $course->id) }}"
                                    class="text-blue-600 hover:underline mr-3">
                                    Edit
                                </a>

                                <form class="inline-block"
                                    action="{{ route('teacher.courses.destroy', parameters: $course->id) }}"
                                    method="POST" onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600
                                    hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($courses->count() === 0)
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                You have no courses.
                            </td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
