@extends('admin.layouts.app')

@section('title', 'Tạo Menu')

@section('content')
<h1 class="text-xl font-bold mb-4">Tạo Menu Mới</h1>

<form method="POST" action="{{ route('admin.menus.store') }}" class="max-w-md space-y-4">
    @csrf

    <div>
        <label class="block font-semibold mb-1" for="name">Tên Menu</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required
               class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block font-semibold mb-1" for="location">Vị Trí Menu</label>
        <input type="text" name="location" id="location" value="{{ old('location') }}" required
               placeholder="header, footer, sidebar"
               class="w-full border rounded px-3 py-2 @error('location') border-red-500 @enderror">
        @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Tạo Menu</button>
    <a href="{{ route('admin.menus.index') }}" class="ml-4 text-gray-600 hover:underline">Hủy</a>
</form>
@endsection