<!-- resources/views/admin/pages/index.blade.php -->

@extends('admin.layouts.app')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Quản Lý Trang</h2>
            <a href="{{ route('admin.pages.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Tạo Trang Mới
            </a>
        </div>

        {{-- Filter Form --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border">
            <form method="GET" action="{{ route('admin.pages.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề..."
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Tất cả</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        <svg class="w-5 h-5 inline-block -mt-1 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc
                    </button>
                    <a href="{{ route('admin.pages.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">Reset</a>
                </div>
            </form>
        </div>

        {{-- Pages Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ tự</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày đăng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pages as $page)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $page->id }}</td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $page->parent ? '└─ ' : '' }}{{ $page->title }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Bởi: {{ $page->user->name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $page->slug }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                    {{ ucfirst(str_replace('-', ' ', $page->template)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" value="{{ $page->order }}"
                                    class="order-input w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                    data-page-id="{{ $page->id }}" min="0">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded 
                                            @if($page->status === 'published' && $page->published_at > now()) bg-blue-100 text-blue-800
                                            @elseif($page->status === 'published') bg-green-100 text-green-800
                                            @elseif($page->status === 'draft') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                    {{ $page->status === 'published' && $page->published_at > now() ? 'Scheduled' : ucfirst($page->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $page->published_at ? $page->published_at->format('d/m/Y H:i') : '-' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.pages.edit', $page) }}"
                                        class="text-blue-600 hover:text-blue-900">Sửa</a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa trang này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Chưa có trang nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pages->appends(request()->query())->links() }}
        </div>
    </div>
@endsection