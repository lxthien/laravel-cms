<!-- resources/views/admin/posts/by-category.blade.php -->

@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Header với Breadcrumb --}}
    <div class="mb-6">
        {{-- Breadcrumb --}}
        <nav class="text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.posts.index') }}" class="hover:text-blue-600">Bài viết</a>
            <span class="mx-2">/</span>
            <span>Danh mục:</span>
            @foreach($breadcrumbs as $crumb)
                @if(!$loop->last)
                    <a href="{{ route('admin.posts.by-category', $crumb) }}" class="hover:text-blue-600">
                        {{ $crumb->name }}
                    </a>
                    <span class="mx-2">›</span>
                @else
                    <span class="font-semibold text-gray-900">{{ $crumb->name }}</span>
                @endif
            @endforeach
        </nav>
        
        {{-- Title & Actions --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Bài viết trong danh mục: {{ $category->name }}</h1>
                <p class="text-gray-600 mt-1">
                    Tổng: <strong>{{ $posts->total() }}</strong> bài viết
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.posts.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tạo Bài Viết Mới
                </a>
                <a href="{{ route('admin.posts.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Về Danh Sách
                </a>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.posts.by-category', $category) }}" class="flex gap-4 items-end">
            {{-- Search --}}
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Tìm theo tiêu đề hoặc nội dung..."
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Status Filter --}}
            <div class="w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Lọc
                </button>
                <a href="{{ route('admin.posts.by-category', $category) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Active Filters --}}
    @if(request()->hasAny(['search', 'status']))
        <div class="mb-4 flex items-center gap-2">
            <span class="text-sm text-gray-600">Đang lọc:</span>
            @if(request('search'))
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm inline-flex items-center">
                    Tìm: "{{ request('search') }}"
                    <a href="{{ route('admin.posts.by-category', array_merge(['category' => $category->id], array_diff_key(request()->all(), ['search' => '']))) }}" 
                       class="ml-2 hover:text-blue-900">×</a>
                </span>
            @endif
            @if(request('status'))
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm inline-flex items-center">
                    Trạng thái: {{ ucfirst(request('status')) }}
                    <a href="{{ route('admin.posts.by-category', array_merge(['category' => $category->id], array_diff_key(request()->all(), ['status' => '']))) }}" 
                       class="ml-2 hover:text-purple-900">×</a>
                </span>
            @endif
        </div>
    @endif

    {{-- Posts Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tác giả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $post->id }}
                        </td>
                        
                        {{-- Featured Image --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="h-12 w-16 object-cover rounded shadow-sm">
                            @else
                                <div class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        
                        {{-- Title & Slug --}}
                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-sm font-medium text-gray-900 truncate">
                                {{ $post->title }}
                            </div>
                            <div class="text-xs text-gray-500 truncate">{{ $post->slug }}</div>
                        </td>
                        
                        {{-- Categories --}}
                        <td class="px-6 py-4">
                            @if($post->categories->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($post->categories as $cat)
                                        <a href="{{ route('admin.posts.by-category', $cat) }}" 
                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition
                                                  {{ $cat->pivot->is_primary ? 'bg-blue-100 text-blue-800 hover:bg-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}
                                                  {{ $cat->id == $category->id ? 'ring-2 ring-blue-500' : '' }}">
                                            {{ $cat->name }}
                                            @if($cat->pivot->is_primary)
                                                <svg class="ml-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm italic">-</span>
                            @endif
                        </td>
                        
                        {{-- Author --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $post->user->name }}
                        </td>
                        
                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                         {{ $post->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $post->status == 'published' ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        
                        {{-- Published Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}
                        </td>
                        
                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.posts.edit', $post) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition">
                                    Sửa
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Không có bài viết</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Chưa có bài viết nào trong danh mục "{{ $category->name }}"
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('admin.posts.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tạo Bài Viết Mới
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $posts->appends(request()->query())->links() }}
    </div>
</div>
@endsection