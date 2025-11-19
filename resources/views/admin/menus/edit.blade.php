@extends('admin.layouts.app')

@section('title', 'Sửa Menu')

@section('content')
<h1 class="text-xl font-bold mb-4">Sửa Menu</h1>

<form method="POST" action="{{ route('admin.menus.update', $menu) }}" class="max-w-md space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold mb-1" for="name">Tên Menu</label>
        <input type="text" name="name" id="name" value="{{ old('name', $menu->name) }}" required
               class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1" for="location">Vị Trí Menu</label>
        <input type="text" name="location" id="location" value="{{ old('location', $menu->location) }}" required
               class="w-full border rounded px-3 py-2" placeholder="header, footer, sidebar">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Lưu thay đổi</button>
    <a href="{{ route('admin.menus.index') }}" class="ml-4 text-gray-600 hover:underline">Hủy</a>
</form>
@endsection