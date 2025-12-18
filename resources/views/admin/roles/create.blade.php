@extends('admin.layouts.app')

@section('title', 'Thêm vai trò mới')
@section('page-title', 'Thêm vai trò')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Tạo vai trò mới</h2>
            <a href="{{ route('admin.roles.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
                        Tên vai trò <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="vd: Moderator, Content Manager..." required autofocus>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Phân quyền (Permissions)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($permissions as $group => $perms)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <h4 class="text-sm font-bold text-gray-700 uppercase mb-3 pb-2 border-b border-gray-200">
                                    {{ ucfirst($group) }}
                                </h4>
                                <div class="space-y-2">
                                    @foreach($perms as $permission)
                                        <label class="flex items-center group cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                                {{ $permission->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow transition duration-200">
                    Lưu vai trò
                </button>
                <a href="{{ route('admin.roles.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 px-6 rounded-lg transition duration-200">
                    Hủy
                </a>
            </div>
        </form>
    </div>
@endsection