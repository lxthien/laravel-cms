@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tổng Quan')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Posts Card --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Tổng Bài Viết</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['posts']) }}</p>
                </div>
            </div>
        </div>

        {{-- Pages Card --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Tổng Trang</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['pages']) }}</p>
                </div>
            </div>
        </div>

        {{-- Categories Card --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Danh Mục</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['categories']) }}</p>
                </div>
            </div>
        </div>

        {{-- Users Card --}}
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="mb-2 text-sm font-medium text-gray-600">Thành Viên</p>
                    <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['users']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Posts --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Bài Viết Mới Nhất</h3>
            <a href="{{ route('admin.posts.index') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                Xem tất cả
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu Đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh Mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tác Giả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Đăng</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPosts as $post)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($post->categories->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($post->categories->take(2) as $category)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                        @if($post->categories->count() > 2)
                                            <span class="text-xs text-gray-500">+{{ $post->categories->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $post->user->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded 
                                            @if($post->status === 'published') bg-green-100 text-green-800
                                            @elseif($post->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Chưa có bài viết nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection