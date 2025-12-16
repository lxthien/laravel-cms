@extends('admin.layouts.app')

@section('title', 'Quản Lý Liên Hệ')
@section('page-title', 'Liên Hệ')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Danh Sách Liên Hệ</h2>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter Form --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm theo tên, email, số điện thoại..."
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        <svg class="w-5 h-5 inline-block -mt-1 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc
                    </button>
                    <a href="{{ route('admin.contacts.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người Gửi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thông Tin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tin Nhắn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Gửi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $contact->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $contact->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">
                                        {{ $contact->email }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">{{ $contact->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span title="{{ $contact->message }}" class="text-sm text-gray-600">
                                    {{ \Str::limit($contact->message, 50) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $contact->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        Chi tiết
                                    </a>
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Chưa có liên hệ nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $contacts->appends(request()->query())->links() }}
        </div>
    </div>
@endsection