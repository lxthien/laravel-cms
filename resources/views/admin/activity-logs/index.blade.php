@extends('admin.layouts.app')

@section('title', 'Nhật ký hoạt động')
@section('page-title', 'Nhật Ký Hoạt Động')

@section('content')
    <div class="space-y-6">
        {{-- Filter Section --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Lọc hoạt động</h3>

            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Log Type Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại hành động</label>
                        <select name="log_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                            <option value="">Tất cả</option>
                            @foreach(['create' => 'Tạo mới', 'update' => 'Cập nhật', 'delete' => 'Xóa', 'login' => 'Đăng nhập', 'logout' => 'Đăng xuất', 'setting_change' => 'Thay đổi cài đặt'] as $key => $label)
                                <option value="{{ $key }}" {{ request('log_type') == $key ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- User Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Người dùng</label>
                        <select name="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                            <option value="">Tất cả</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                    </div>
                </div>

                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm mô tả</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập mô tả hoạt động..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500">
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        <i class="fas fa-search mr-2"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        <i class="fas fa-redo mr-2"></i>Đặt lại
                    </a>
                    <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        <i class="fas fa-download mr-2"></i>Xuất CSV
                    </a>
                    <a href="{{ route('admin.activity-logs.statistics') }}"
                        class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-purple-600">
                        <i class="fas fa-chart-bar mr-2"></i>Thống kê
                    </a>
                </div>
            </form>
        </div>

        {{-- Activity Logs Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Hành động</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Mô tả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Loại đối tượng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @include('admin.activity-logs.partials._log-badge', ['log' => $log])
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($log->description, 50) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($log->subject_type)
                                    <span
                                        class="px-2 py-1 bg-gray-100 rounded text-xs">{{ class_basename($log->subject_type) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <time title="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.activity-logs.show', $log) }}"
                                    class="text-blue-600 hover:text-blue-900 font-medium">
                                    <i class="fas fa-eye mr-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                <p class="mt-2">Không có hoạt động nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $logs->links() }}
        </div>
    </div>
@endsection