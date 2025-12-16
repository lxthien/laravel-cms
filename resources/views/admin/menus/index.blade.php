@extends('admin.layouts.app')

@section('title', 'Quản Lý Menu')
@section('page-title', 'Menu')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Danh Sách Menu</h2>

            <a href="{{ route('admin.menus.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Thêm Menu
            </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vị trí</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($menus as $menu)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $menu->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                            </td>
                            <td class="px-6 py-4 capitalize text-sm text-gray-600">
                                {{ $menu->location }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-4 items-center">
                                    <a href="{{ route('admin.menu-items.index', ['menu_id' => $menu->id]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Menu Items
                                    </a>

                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.menus.edit', $menu) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            Sửa
                                        </a>

                                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa menu này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Chưa có menu nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </div>
@endsection