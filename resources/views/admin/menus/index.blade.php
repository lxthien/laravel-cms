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
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                        class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-md font-bold text-xs hover:bg-blue-100 transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Thiết kế Menu
                                    </a>

                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa menu này? Toàn bộ các mục bên trong sẽ bị xóa vĩnh viễn.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
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