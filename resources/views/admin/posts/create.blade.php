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
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
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
                    <textarea id="tinymce" name="content">{{ old('content') }}</textarea>
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
                
                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        Danh Mục <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags với Select2 -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                        Tags
                        <span class="text-xs text-gray-500">(Nhập và nhấn Enter để tạo mới)</span>
                    </label>
                    <select name="tags[]" id="tags" multiple class="w-full">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Bạn có thể chọn tag có sẵn hoặc nhập tag mới</p>
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

@push('scripts')
<script src="https://cdn.tiny.cloud/1/8gma6mvzjqqnn5p4u29fi9ewfme5p5kzrmqatg4x6wpwyjxr/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
    tinymce.init({
        selector: '#tinymce',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        
        // Validation khi submit form
        setup: function(editor) {
            editor.on('init', function() {
                // Khi form submit, copy nội dung từ TinyMCE vào textarea
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    // Lấy nội dung từ TinyMCE
                    const content = tinymce.get('tinymce').getContent();
                    
                    // Kiểm tra nếu trống
                    if (!content.trim()) {
                        e.preventDefault();
                        alert('Vui lòng nhập nội dung bài viết!');
                        return false;
                    }
                    
                    // Copy vào textarea
                    document.getElementById('tinymce').value = content;
                });
            });
        }
    });
</script>
@endpush

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
$(document).ready(function() {
    $('#tags').select2({
        tags: true,                          // Enable tagging
        tokenSeparators: [',', ' '],        // Separate by comma or space
        placeholder: "Chọn hoặc nhập tags...",
        allowClear: true,
        
        // Customize new tag creation
        createTag: function (params) {
            var term = $.trim(params.term);
            
            if (term === '') {
                return null;
            }
            
            // Validate tag length
            if (term.length > 50) {
                return null;
            }
            
            return {
                id: 'new:' + term,           // Prefix with 'new:' for new tags
                text: term + ' (mới)',       // Show indicator for new tags
                newTag: true
            }
        },
        
        // Insert new tags at the end
        insertTag: function (data, tag) {
            data.push(tag);
        }
    });
});
</script>
@endpush