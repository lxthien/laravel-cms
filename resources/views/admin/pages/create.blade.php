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
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Nhập tiêu đề trang..." required>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Slug (Tự động tạo nếu để trống)
                        </label>
                        <input type="text" name="slug" value="{{ old('slug') }}"
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
                        <textarea name="excerpt" rows="3"
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
                        <textarea name="content" id="editor"
                            class="w-full border border-gray-300 rounded px-4 py-2">{{ old('content') }}</textarea>
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
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                    class="w-full border border-gray-300 rounded px-4 py-2"
                                    placeholder="Tiêu đề SEO (để trống sẽ dùng tiêu đề chính)">
                            </div>

                            {{-- Meta Description --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <textarea name="meta_description" rows="3"
                                    class="w-full border border-gray-300 rounded px-4 py-2"
                                    placeholder="Mô tả SEO...">{{ old('meta_description') }}</textarea>
                            </div>

                            {{-- Meta Keywords --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
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
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                            </select>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium">
                                Tạo Trang
                            </button>
                            <a href="{{ route('admin.pages.index') }}"
                                class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded font-medium">
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
                        <div id="featured_image_preview" class="mb-3 hidden">
                            <img src="" class="max-w-full h-auto rounded shadow-sm border border-gray-200">
                        </div>

                        <input type="hidden" name="featured_image" id="featured_image_path" value="{{ old('featured_image') }}">
                        
                        <div class="flex flex-col gap-2">
                            <button type="button" onclick="openMediaManager()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-images mr-1"></i> Chọn từ Media
                            </button>
                            <button type="button" onclick="removeFeaturedImage()" id="btn_remove_image"
                                class="bg-red-100 text-red-600 hover:bg-red-200 px-4 py-2 rounded text-sm font-medium transition-colors hidden">
                                <i class="fas fa-trash-alt mr-1"></i> Xóa ảnh
                            </button>
                        </div>
                    </div>

                    {{-- Gallery Images --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Gallery Images</label>
                        
                        <div id="gallery_preview" class="grid grid-cols-3 gap-2 mb-3">
                            {{-- Selected gallery images will appear here --}}
                        </div>

                        <div id="gallery_inputs">
                            {{-- Hidden inputs for gallery paths --}}
                        </div>

                        <button type="button" onclick="openGalleryManager()"
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-photo-video mr-1"></i> Thêm ảnh Gallery
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Có thể chọn nhiều hình ảnh cùng lúc.</p>
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
                        <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                            class="w-full border border-gray-300 rounded px-4 py-2">
                        <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                    </div>

                    {{-- Options --}}
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold mb-4">Tùy chọn</h3>

                        <div class="space-y-3">
                            {{-- Show in Menu --}}
                            <label class="flex items-center">
                                <input type="checkbox" name="show_in_menu" value="1" {{ old('show_in_menu') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Hiển thị trong Menu</span>
                            </label>

                            {{-- Is Homepage --}}
                            <label class="flex items-center">
                                <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Đặt làm Trang chủ</span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function openMediaManager() {
        MediaManager.open(function(media) {
            document.getElementById('featured_image_path').value = media.file_path;
            
            const preview = document.getElementById('featured_image_preview');
            preview.querySelector('img').src = media.url;
            preview.classList.remove('hidden');
            
            document.getElementById('btn_remove_image').classList.remove('hidden');
        }, false);
    }

    function removeFeaturedImage() {
        document.getElementById('featured_image_path').value = '';
        document.getElementById('featured_image_preview').classList.add('hidden');
        document.getElementById('btn_remove_image').classList.add('hidden');
    }

    // Gallery Management
    let galleryImages = [];

    function openGalleryManager() {
        MediaManager.open(function (mediaList) {
            // mediaList is an array when multiple = true
            mediaList.forEach(media => {
                // Avoid duplicates
                if (!galleryImages.some(img => img.file_path === media.file_path)) {
                    galleryImages.push({
                        file_path: media.file_path,
                        url: media.url
                    });
                }
            });
            renderGallery();
        }, true); // true = multiple selection
    }

    function renderGallery() {
        const preview = document.getElementById('gallery_preview');
        const inputs = document.getElementById('gallery_inputs');
        
        if (!preview || !inputs) return;

        preview.innerHTML = '';
        inputs.innerHTML = '';

        galleryImages.forEach((img, index) => {
            // Render preview
            const col = document.createElement('div');
            col.className = 'relative group';
            col.innerHTML = `
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 lg:aspect-none group-hover:opacity-75 h-24">
                    <img src="${img.url}" class="h-full w-full object-cover object-center">
                </div>
                <button type="button" onclick="removeFromGallery(${index})" 
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            preview.appendChild(col);

            // Render hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'gallery[]';
            input.value = img.file_path;
            inputs.appendChild(input);
        });
    }

    function removeFromGallery(index) {
        galleryImages.splice(index, 1);
        renderGallery();
    }
</script>
@endpush
