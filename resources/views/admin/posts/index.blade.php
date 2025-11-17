@extends('admin.layouts.app')

@section('title', 'Quản Lý Bài Viết')
@section('page-title', 'Bài Viết')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Danh Sách Bài Viết</h2>
        
        @can('post-create')
        <a href="{{ route('admin.posts.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tạo Bài Viết Mới
        </a>
        @endcan
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hình</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu Đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh Mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tác Giả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Đăng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if (!empty($post->featured_image))
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Ảnh đại diện hiện tại"
                                class="max-w-xs max-h-48 rounded shadow border" width="50">
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                        <div class="text-sm text-gray-500">{{ $post->slug }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $post->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $post->user->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded 
                            @if($post->status === 'published') bg-green-100 text-green-800
                            @elseif($post->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        {{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            @can('post-edit')
                            <a href="{{ route('admin.posts.edit', $post) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                Sửa
                            </a>
                            @endcan
                            
                            @can('post-delete')
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Xóa
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Chưa có bài viết nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection