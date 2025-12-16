@extends('admin.layouts.app')

@section('title', 'Quản Lý Bình Luận')
@section('page-title', 'Bình Luận')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Danh Sách Bình Luận</h2>
        </div>

        {{-- Filter Form --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border">
            <form method="GET" action="{{ route('admin.comments.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm theo nội dung, người gửi, bài viết..."
                        class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Tất cả</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="spam" {{ request('status') == 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        <svg class="w-5 h-5 inline-block -mt-1 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc
                    </button>
                    <a href="{{ route('admin.comments.index') }}"
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội Dung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bài Viết</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày Tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($comments as $comment)
                        <tr>
                            <td class="px-6 py-4">{{ $comment->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $comment->user->name ?? $comment->name }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $comment->user->email ?? $comment->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span title="{{ $comment->content }}">{{ \Str::limit($comment->content, 50) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" target="_blank" class="text-blue-600 hover:underline">
                                    {{ \Str::limit($comment->post->title ?? 'Bài viết đã xoá', 30) }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded 
                                                                    @if($comment->status === 'approved') bg-green-100 text-green-800
                                                                    @elseif($comment->status === 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif($comment->status === 'spam') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                    {{ $comment->status == 'approved' ? 'Đã duyệt' : ($comment->status == 'pending' ? 'Chờ duyệt' : ucfirst($comment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $comment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    {{-- Status Actions --}}
                                    @if ($comment->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.comments.update-status', $comment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Duyệt">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($comment->status !== 'pending')
                                        <form method="POST" action="{{ route('admin.comments.update-status', $comment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Chờ duyệt">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($comment->status !== 'spam')
                                        <form method="POST" action="{{ route('admin.comments.update-status', $comment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="spam">
                                            <button type="submit" class="text-gray-600 hover:text-gray-900" title="Spam">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Chưa có bình luận nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $comments->appends(request()->query())->links() }}
        </div>
    </div>
@endsection