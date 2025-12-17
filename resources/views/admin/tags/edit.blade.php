@extends('admin.layouts.app')
@section('title', 'Chỉnh Sửa Thẻ')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Chỉnh Sửa Thẻ: {{ $tag->name }}</h2>
            <a href="{{ route('admin.tags.index') }}" class="text-gray-500 hover:underline">Quay lại danh sách</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tên Thẻ <span
                            class="text-red-500">*</span></label>
                    <input name="name" type="text" value="{{ old('name', $tag->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"
                        required>
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                    <input type="text" value="{{ $tag->slug }}" disabled
                        class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-2">Slug được cập nhật tự động khi thay đổi tên.</p>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        Cập Nhật
                    </button>
                    <a href="{{ route('admin.tags.index') }}" class="text-gray-600 hover:text-gray-800">Hủy bỏ</a>
                </div>
            </form>
        </div>
    </div>
@endsection