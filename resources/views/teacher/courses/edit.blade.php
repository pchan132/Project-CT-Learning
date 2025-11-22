<x-app-layout>
    <div class="max-w-xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">Edit Course</h1>

        <form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow p-6 rounded-lg space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium">Title</label>
                <input type="text" name="title" value="{{ $course->title }}" class="w-full border rounded-md p-2 mt-1"
                    required>
            </div>

            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" class="w-full border rounded-md p-2 mt-1" rows="4">{{ $course->description }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Current Cover Image</label>
                @if ($course->cover_image_url)
                    <img src="{{ asset('storage/' . $course->cover_image_url) }}" alt="Cover Image"
                        class="h-32 w-32 object-cover rounded mt-2">
                @else
                    <p class="text-gray-500 mt-2">No cover image uploaded</p>
                @endif
            </div>

            <div>
                <label class="block font-medium">Upload New Cover Image</label>
                <input type="file" name="cover_image_url" accept="image/*" class="mt-2">
                <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('teacher.courses.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Back
                </a>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
