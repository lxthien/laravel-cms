@extends('admin.layouts.app')

@section('title', 'Tạo Bài Viết Mới')
@section('page-title', 'Tạo Bài Viết Mới')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-3 gap-6">
                <!-- Main Content (Left) -->
                <div class="col-span-2">

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Tiêu Đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                            required>
                        @error('title')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                            Slug (Tự động tạo nếu để trống)
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="excerpt">
                            Mô Tả Ngắn
                        </label>
                        <textarea name="excerpt" id="excerpt" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('excerpt') }}</textarea>
                    </div>

                    <!-- Content với TinyMCE -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tinymce">
                            Nội Dung <span class="text-red-500">*</span>
                        </label>
                        <textarea id="editor" name="content">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t pt-4 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Tối Ưu SEO</h3>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_title">
                                Meta Title
                            </label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_description">
                                Meta Description
                            </label>
                            <textarea name="meta_description" id="meta_description" rows="3"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('meta_description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="meta_keywords">
                                Meta Keywords (phân cách bằng dấu phẩy)
                            </label>
                            <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    </div>

                </div>

                <!-- Sidebar (Right) -->
                <div class="col-span-1">

                    <!-- Categories (Multiple Select với Primary) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Danh Mục <span class="text-red-500">*</span>
                        </label>

                        <select name="categories[]" id="categories" multiple class="w-full border rounded px-3 py-2 h-48">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ collect(old('categories'))->contains($category->id) ? 'selected' : '' }}>
                                    {{ str_repeat('—', $category->level ?? 0) }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Giữ Ctrl (Cmd) để chọn nhiều danh mục</p>
                        @error('categories')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Primary Category Radio -->
                        <div class="mt-3" id="primary-category-container" style="display: none;">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Danh mục chính
                            </label>
                            <div id="primary-category-radios" class="space-y-2">
                                <!-- Will be populated by JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                            Tags
                        </label>
                        <select name="tags[]" id="tags" multiple class="w-full">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ collect(old('tags'))->contains($tag->id) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Nhập tag và nhấn <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Enter</kbd> để thêm
                        </p>
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="featured_image">
                            Ảnh Đại Diện
                        </label>
                        <input type="file" name="featured_image" id="featured_image" accept="image/*"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        @error('featured_image')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Trạng Thái <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    <!-- Published At -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="published_at">
                            Ngày Đăng
                        </label>
                        <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col gap-2 mt-6">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            Đăng Bài
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center w-full">
                            Hủy
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        /* Customize Select2 */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            min-height: 42px;
            padding: 4px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Set primary category ID to null for new posts
        window.currentPrimaryCategoryId = null;
    </script>
@endpush