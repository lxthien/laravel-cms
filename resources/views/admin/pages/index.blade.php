<!-- resources/views/admin/pages/index.blade.php -->

@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Quản Lý Trang</h1>
        <a href="{{ route('admin.pages.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Tạo Trang Mới
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Tìm theo tiêu đề..."
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
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Lọc</button>
                <a href="{{ route('admin.pages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">Reset</a>
            </div>
        </form>
    </div>

    {{-- Pages Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ tự</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Menu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Homepage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pages as $page)
                    <tr class="hover:bg-gray-50">
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
                            <code class="bg-gray-100 px-2 py-1 rounded">{{ $page->slug }}</code>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                {{ ucfirst(str_replace('-', ' ', $page->template)) }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" 
                                   value="{{ $page->order }}" 
                                   class="order-input w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                   data-page-id="{{ $page->id }}"
                                   min="0">
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="status-toggle px-2 py-1 rounded text-xs font-semibold
                                           {{ $page->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}"
                                    data-page-id="{{ $page->id }}"
                                    data-current-status="{{ $page->status }}">
                                {{ ucfirst($page->status) }}
                            </button>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($page->show_in_menu)
                                <svg class="h-5 w-5 text-green-500 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($page->is_homepage)
                                <svg class="h-5 w-5 text-blue-500 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900 mr-3">Sửa</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa trang này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                            </form>
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

    <div class="mt-6">
        {{ $pages->appends(request()->query())->links() }}
    </div>
</div>

{{-- AJAX for Order & Status --}}
@push('scripts')
<script>
$(document).ready(function() {
    // Update Order
    $('.order-input').on('change', function() {
        const pageId = $(this).data('page-id');
        const newOrder = $(this).val();
        
        $.ajax({
            url: `/admin/pages/${pageId}/update-order`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                order: newOrder
            },
            success: function(response) {
                alert(response.message);
            }
        });
    });
    
    // Toggle Status
    $('.status-toggle').on('click', function() {
        const pageId = $(this).data('page-id');
        const button = $(this);
        
        $.ajax({
            url: `/admin/pages/${pageId}/toggle-status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                button.data('current-status', response.status);
                button.text(response.status.charAt(0).toUpperCase() + response.status.slice(1));
                
                if (response.status === 'published') {
                    button.removeClass('bg-yellow-100 text-yellow-800').addClass('bg-green-100 text-green-800');
                } else {
                    button.removeClass('bg-green-100 text-green-800').addClass('bg-yellow-100 text-yellow-800');
                }
            }
        });
    });
});
</script>
@endpush
@endsection