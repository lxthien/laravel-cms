@extends('admin.layouts.app')

@section('title', 'Quản Lý Danh Mục')
@section('page-title', 'Danh Mục')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Danh Sách Danh Mục</h2>
        
        @can('category-create')
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tạo Danh Mục Mới
        </a>
        @endcan
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội Dung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bài Viết</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($comments as $c)
                <tr>
                    <td class="px-6 py-4">{{ $c->id }}</td>
                    <td class="px-6 py-4">{{ $c->user->name ?? $c->name }}</td>
                    <td class="px-6 py-4">{{ \Str::limit($c->content, 50) }}</td>
                    <td class="px-6 py-4">{{ $c->post->title }}</td>
                    <td class="px-6 py-4">{{ $c->status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            @if($c->status != 'approved')
                            <form method="POST" action="{{ route('admin.comments.approve', $c->id) }}">
                                @csrf <button class="text-blue-600 hover:text-blue-900">Duyệt</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.comments.destroy', $c->id) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900">Xoá</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection