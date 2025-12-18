@extends('admin.layouts.app')

@section('title', 'Quản lý vai trò')
@section('page-title', 'Danh sách vai trò')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Vai trò & Phân quyền</h2>
            <a href="{{ route('admin.roles.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-1"></i> Thêm vai trò mới
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Tên vai trò</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700">Permissions</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-700 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 capitalize">{{ $role->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @forelse($role->permissions as $permission)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs italic">Không có permissions</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                        class="text-blue-500 hover:text-blue-700 transition-colors" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($role->name !== 'admin')
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors"
                                                title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection