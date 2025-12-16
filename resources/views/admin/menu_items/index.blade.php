@extends('admin.layouts.app')

@section('title', 'Quản Lý Menu Items')
@section('page-title', 'Menu Items')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">
                Menu Items: <span class="text-blue-600">{{ $menu->name }}</span>
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.menus.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                    ← Quay lại Menu
                </a>
                <a href="{{ route('admin.menu-items.create', ['menu_id' => $menu->id]) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Thêm Menu Item
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ tự</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menuItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @if($item->parent_id)
                                        <span class="text-gray-400 mr-1">└─</span>
                                    @endif
                                    {{ $item->title }}
                                </div>
                                @if($item->icon)
                                    <div class="text-xs text-gray-500 mt-1">Icon: {{ $item->icon }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs" title="{{ $item->url }}">
                                {{ $item->url }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($item->target === '_blank')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">New Tab</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Self</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->order ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.menu-items.edit', $item) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        Sửa
                                    </a>
                                    <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa menu item này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Chưa có item nào trong menu này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection