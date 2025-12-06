<!-- resources/views/admin/pages/create.blade.php -->

@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Tạo Trang Mới</h1>
        <p class="text-gray-600 mt-1">Tạo trang tĩnh cho website</p>
    </div>

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-3 gap-6">
            {{-- Main Content Column --}}
            <div class="col-span-2 space-y-6">
                
                {{-- Title --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tiêu đề <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Nhập tiêu đề trang..."
                           required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Slug (Tự động tạo nếu để trống)
                    </label>
                    <input type="text" 
                           name="slug" 
                           value="{{ old('slug') }}"
                           class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="vd: gioi-thieu, lien-he">
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Excerpt --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Mô tả ngắn
                    </label>
                    <textarea name="excerpt" 
                              rows="3"
                              class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Mô tả ngắn về trang...">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Nội dung <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" 
                              id="editor"
                              class="w-full border border-gray-300 rounded px-4 py-2"
                              >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO Meta --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">SEO Meta Tags</h3>
                    
                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" 
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2"
                                   placeholder="Tiêu đề SEO (để trống sẽ dùng tiêu đề chính)">
                        </div>

                        {{-- Meta Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded px-4 py-2"
                                      placeholder="Mô tả SEO...">{{ old('meta_description') }}</textarea>
                        </div>

                        {{-- Meta Keywords --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   value="{{ old('meta_keywords') }}"
                                   class="w-full border border-gray-300 rounded px-4 py-2"
                                   placeholder="keyword1, keyword2, keyword3">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar Column --}}
            <div class="col-span-1 space-y-6">
                
                {{-- Publish Box --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">Xuất bản</h3>
                    
                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select name="status" class="w-full border border-gray-300 rounded px-4 py-2">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium">
                            Tạo Trang
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded font-medium">
                            Hủy
                        </a>
                    </div>
                </div>

                {{-- Template --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Template</label>
                    <select name="template" class="w-full border border-gray-300 rounded px-4 py-2">
                        @foreach($templates as $key => $label)
                            <option value="{{ $key }}" {{ old('template') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Layout hiển thị trang</p>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ảnh đại diện</label>
                    <div class="border-2 border-dashed border-gray-300 rounded p-4 text-center">
                        <div id="image-preview" class="hidden mb-2">
                            <img src="" alt="Preview" class="max-w-full h-auto rounded">
                        </div>
                        <input type="file" 
                               name="featured_image" 
                               id="featured_image"
                               accept="image/*"
                               class="hidden">
                        <button type="button" 
                                onclick="document.getElementById('featured_image').click()"
                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm">
                            Chọn ảnh
                        </button>
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF (Max: 2MB)</p>
                    </div>
                    <input type="hidden" name="featured_image" id="featured_image_path">
                </div>

                {{-- Parent Page --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Trang cha</label>
                    <select name="parent_id" class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Không có (Trang gốc)</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->id }}" {{ old('parent_id') == $page->id ? 'selected' : '' }}>
                                {{ $page->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Để tạo cấu trúc phân cấp</p>
                </div>

                {{-- Order --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thứ tự</label>
                    <input type="number" 
                           name="order" 
                           value="{{ old('order', 0) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded px-4 py-2">
                    <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                </div>

                {{-- Options --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">Tùy chọn</h3>
                    
                    <div class="space-y-3">
                        {{-- Show in Menu --}}
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="show_in_menu" 
                                   value="1"
                                   {{ old('show_in_menu') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Hiển thị trong Menu</span>
                        </label>

                        {{-- Is Homepage --}}
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_homepage" 
                                   value="1"
                                   {{ old('is_homepage') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Đặt làm Trang chủ</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image Preview
    $('#featured_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview img').attr('src', e.target.result);
                $('#image-preview').removeClass('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Auto generate slug from title
    $('input[name="title"]').on('blur', function() {
        const title = $(this).val();
        const slugInput = $('input[name="slug"]');
        
        if (!slugInput.val() && title) {
            const slug = title
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.val(slug);
        }
    });
});
</script>
@endpush
@endsection