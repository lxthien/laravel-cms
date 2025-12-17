@extends('admin.layouts.app')

@section('title', 'Tạo Danh Mục Mới')
@section('page-title', 'Tạo Danh Mục Mới')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Tên Danh Mục <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                    Slug (Tự động tạo nếu để trống)
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('slug')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Category -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_id">
                    Danh Mục Cha
                </label>
                <select name="parent_id" id="parent_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Không có --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Mô Tả
                </label>
                <textarea name="description" id="description" rows="4"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Hình Ảnh
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('image')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta Title -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">
                    Meta Title (SEO)
                </label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Meta Description -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">
                    Meta Description (SEO)
                </label>
                <textarea name="meta_description" id="meta_description" rows="3"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('meta_description') }}</textarea>
            </div>

            <!-- Meta Keywords -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_keywords">
                    Meta Keywords
                </label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Robots Meta -->
            <div class="mb-4 flex gap-6">
                <label class="flex items-center">
                    <input type="checkbox" name="index" value="1" {{ old('index', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 text-sm font-bold">Index (Cho phép Google lập chỉ mục)</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="follow" value="1" {{ old('follow', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 text-sm font-bold">Follow (Cho phép Google theo dõi liên kết)</span>
                </label>
            </div>

            <!-- Order -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="order">
                    Thứ Tự
                </label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-gray-700 text-sm font-bold">Kích hoạt</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Tạo Danh Mục
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Hủy
                </a>
            </div>
        </form>
    </div>
@endsection