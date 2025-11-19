@extends('admin.layouts.app')

@section('title', 'Quản Lý Menu')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-xl font-bold">Danh Sách Menu</h1>
    <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Thêm Menu</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
@endif

<table class="min-w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Tên</th>
            <th class="border p-2">Vị trí</th>
            <th class="border p-2">Hành Động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($menus as $menu)
        <tr>
            <td class="border p-2">{{ $menu->id }}</td>
            <td class="border p-2">{{ $menu->name }}</td>
            <td class="border p-2 capitalize">{{ $menu->location }}</td>
            <td class="border p-2 space-x-2">
                <a href="{{ route('admin.menu-items.index', ['menu_id' => $menu->id]) }}" class="text-blue-500">Menu Items</a>
                <a href="{{ route('admin.menus.edit', $menu) }}" class="text-yellow-600">Sửa</a>
                <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="inline" onsubmit="return confirm('Xóa menu này chắc chắn?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td class="border p-2 text-center" colspan="4">Chưa có menu nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $menus->links() }}
</div>
@endsection