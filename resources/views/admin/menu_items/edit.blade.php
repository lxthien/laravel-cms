@extends('admin.layouts.app')

@section('title', 'Sửa Menu Item')

@section('content')
<h1 class="text-xl font-bold mb-4">Sửa Menu Item cho: <span class="text-blue-600">{{ $menuItem->menu->name }}</span></h1>

<form method="POST" action="{{ route('admin.menu-items.update', $menuItem) }}" class="max-w-lg space-y-4">
    @csrf
    @method('PUT')

    <input type="hidden" name="menu_id" value="{{ $menuItem->menu->id }}">

    <div>
        <label class="block font-semibold mb-1" for="title">Tiêu Đề</label>
        <input type="text" name="title" id="title" value="{{ old('title', $menuItem->title) }}" required
               class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1" for="url">URL</label>
        <input type="text" name="url" id="url" value="{{ old('url', $menuItem->url) }}" required
               class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1" for="parent_id">Parent (nếu có)</label>
        <select name="parent_id" id="parent_id" class="w-full border rounded px-3 py-2">
            <option value="">-- None --</option>
            @foreach ($parentItems as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $menuItem->parent_id) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1" for="target">Mở link</label>
        <select name="target" id="target" class="w-full border rounded px-3 py-2">
            <option value="_self" {{ $menuItem->target == '_self' ? 'selected' : '' }}>Mở trang hiện tại</option>
            <option value="_blank" {{ $menuItem->target == '_blank' ? 'selected' : '' }}>Mở trang mới</option>
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1" for="order">Thứ tự</label>
        <input type="number" name="order" id="order" value="{{ old('order', $menuItem->order) }}" class="w-full border rounded px-3 py-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Lưu thay đổi</button>
    <a href="{{ route('admin.menu-items.index', ['menu_id' => $menuItem->menu->id]) }}" class="ml-4 text-gray-600 hover:underline">Hủy</a>
</form>
@endsection