@extends('admin.layouts.app')

@section('title', 'Quản Lý Menu Items')

@section('content')
<h1 class="text-xl font-bold mb-4">Menu Items của: <span class="text-blue-600">{{ $menu->name }}</span></h1>

<div class="mb-4">
    <a href="{{ route('admin.menu-items.create', ['menu_id' => $menu->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Thêm Menu Item
    </a>
    <a href="{{ route('admin.menus.index') }}" class="ml-4 text-gray-600 hover:underline">← Quay lại Menu</a>
</div>

<table class="min-w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Tiêu đề</th>
            <th class="border p-2">URL</th>
            <th class="border p-2">Parent</th>
            <th class="border p-2">Thứ tự</th>
            <th class="border p-2">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($menuItems as $item)
        <tr>
            <td class="border p-2">{{ $item->id }}</td>
            <td class="border p-2">{{ $item->title }}</td>
            <td class="border p-2">{{ $item->url }}</td>
            <td class="border p-2">{{ $item->parent ? $item->parent->title : '-' }}</td>
            <td class="border p-2">{{ $item->order ?? 0 }}</td>
            <td class="border p-2 space-x-2">
                <a href="{{ route('admin.menu-items.edit', $item) }}" class="text-yellow-600">Sửa</a>
                <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection