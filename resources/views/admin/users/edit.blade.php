@extends('admin.layouts.app')
@section('title', 'Edit User')

@section('content')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Chỉnh Sửa User: {{ $user->name }}</h2>
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:underline">Quay lại danh sách</a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Main Column --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Thông tin tài khoản</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Họ tên <span class="text-red-500">*</span></label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email <span class="text-red-500">*</span></label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Password Reset Optional --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center border-b pb-2 mb-4">
                        <h3 class="font-bold text-gray-700">Đổi mật khẩu</h3>
                        <span class="text-xs text-gray-500 italic">Để trống nếu không muốn đổi</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu mới</label>
                            <input name="password" type="password" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                            @error('password')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Xác nhận mật khẩu</label>
                            <input name="password_confirmation" type="password" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Cài đặt</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Vai trò <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                            <option value="subscriber" {{ old('role', $user->role) == 'subscriber' ? 'selected' : '' }}>Subscriber</option>
                            <option value="author" {{ old('role', $user->role) == 'author' ? 'selected' : '' }}>Author</option>
                            <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-bold text-sm">Trạng thái</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer" 
                                       {{ old('status', $user->status) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                         <p class="text-xs text-gray-500 mt-1">Bật để cho phép đăng nhập.</p>
                    </div>

                    <div class="border-t pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                            Lưu Thay Đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection