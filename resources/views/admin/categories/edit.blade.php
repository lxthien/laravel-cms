@extends('admin.layouts.app')

@section('title', 'Sửa Danh Mục')
@section('page-title', 'Sửa Danh Mục')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Các fields giống create.blade.php, chỉ thêm value từ $category -->

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Tên Danh Mục <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                    Slug
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('slug') border-red-500 @enderror">
                @error('slug')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Các fields khác tương tự... -->

            <!-- Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}
                        class="mr-2">
                    <span class="text-gray-700 text-sm font-bold">Kích hoạt</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Cập Nhật
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Hủy
                </a>
            </div>
        </form>
    </div>
@endsection