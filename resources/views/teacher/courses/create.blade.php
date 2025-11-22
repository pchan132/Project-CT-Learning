<x-app-layout>
    <div class="max-w-xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">Create Course</h1>

        <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow p-6 rounded-lg space-y-4">
            @csrf

            <div>
                <label class="block font-medium">Title</label>
                <input type="text" name="title" class="w-full border rounded-md p-2 mt-1" required>
            </div>

            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" class="w-full border rounded-md p-2 mt-1" rows="4"></textarea>
            </div>

            <div>
                <label class="block font-medium">Cover Image</label>
                <input type="file" name="cover" accept="image/*" class="mt-2">
            </div>

            <div class="flex justify-between">
                <a href="{{ route('teacher.courses.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Back
                </a>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
