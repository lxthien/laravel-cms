@extends('admin.layouts.app')

@section('title', 'Sửa Bài Viết')
@section('page-title', 'Sửa Bài Viết')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-3 gap-6">
                <!-- Main Content (Left) -->
                <div class="col-span-2">

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                            Tiêu Đề <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
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
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}"
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
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    <!-- Content với TinyMCE -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tinymce">
                            Nội Dung <span class="text-red-500">*</span>
                        </label>
                        <textarea id="editor" name="content">{{ old('content', $post->content) }}</textarea>
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
                                <option value="{{ $category->id }}" {{ $post->categories->contains($category->id) ? 'selected' : '' }}>
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

                    <!-- Tags với Select2 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="tags">
                            Tags
                        </label>
                        <select name="tags[]" id="tags" multiple class="w-full">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Nhập tag và nhấn <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Enter</kbd> để thêm
                        </p>
                        {{-- Error container sẽ được JS insert vào đây --}}
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Ảnh Đại Diện
                        </label>
                        
                        <div id="featured_image_preview" class="mb-3 {{ empty($post->featured_image) ? 'hidden' : '' }}">
                            <img src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : '' }}" 
                                 class="max-w-full h-auto rounded shadow-sm border border-gray-200">
                        </div>

                        <input type="hidden" name="featured_image" id="featured_image_path" value="{{ $post->featured_image }}">
                        
                        <div class="flex gap-2">
                            <button type="button" onclick="openMediaManager()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                <i class="fas fa-images mr-1"></i> Chọn từ Media
                            </button>
                            <button type="button" onclick="removeFeaturedImage()" id="btn_remove_image"
                                class="bg-red-100 text-red-600 hover:bg-red-200 px-4 py-2 rounded text-sm font-medium transition-colors {{ empty($post->featured_image) ? 'hidden' : '' }}">
                                <i class="fas fa-trash-alt mr-1"></i> Xóa ảnh
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Dung lượng tối đa: 2MB. Định dạng: JPG, PNG, GIF, WebP</p>
                    </div>

                    <!-- Gallery Images -->
                    <div class="mb-4 border-t pt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Gallery Images
                        </label>

                        <div id="gallery_preview" class="grid grid-cols-3 gap-2 mb-3">
                            <!-- Selected gallery images will appear here -->
                        </div>

                        <div id="gallery_inputs">
                            <!-- Hidden inputs for gallery paths -->
                        </div>

                        <button type="button" onclick="openGalleryManager()"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                            <i class="fas fa-photo-video mr-1"></i> Thêm ảnh Gallery
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Có thể chọn nhiều hình ảnh cùng lúc.</p>
                    </div>

                    <!-- Publishing Card -->
                    <div class="bg-gray-50 rounded-lg p-4 border mb-4">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b">
                            Publishing
                        </h3>
                        
                        <!-- Status -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs font-bold mb-1" for="status">
                                Trạng Thái <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full border rounded py-2 px-3 text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Published At -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs font-bold mb-1" for="published_at">
                                Ngày Đăng (Để trống = Giữ nguyên/Ngay bây giờ)
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at" 
                                value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                class="w-full border rounded py-2 px-3 text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('published_at') border-red-500 @enderror">
                            @error('published_at')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 pt-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors">
                                <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                            </button>
                            <a href="{{ route('admin.posts.index') }}"
                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded text-sm text-center transition-colors">
                                Hủy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    window.currentPrimaryCategoryId = {{ $post->primaryCategory() ? $post->primaryCategory()->id : 'null' }};

    function openMediaManager() {
        MediaManager.open(function(media) {
            // Callback: media is the selected media object
            document.getElementById('featured_image_path').value = media.file_path;
            
            const preview = document.getElementById('featured_image_preview');
            preview.querySelector('img').src = media.url;
            preview.classList.remove('hidden');
            
            document.getElementById('btn_remove_image').classList.remove('hidden');
        }, false); // false = single selection
    }

    function removeFeaturedImage() {
        document.getElementById('featured_image_path').value = '';
        document.getElementById('featured_image_preview').classList.add('hidden');
        document.getElementById('btn_remove_image').classList.add('hidden');
    }

    // Gallery Management
    let galleryImages = {!! json_encode(array_map(function($path) {
        return [
            'file_path' => $path,
            'url' => asset('storage/' . $path)
        ];
    }, $post->gallery ?? [])) !!};

    // Initial render
    document.addEventListener('DOMContentLoaded', function() {
        renderGallery();
    });

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