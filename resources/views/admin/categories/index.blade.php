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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Danh Mục Cha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thứ Tự</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành Động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @include('admin.categories._category_tree', ['categories' => $categories, 'level' => 0])
            </tbody>
        </table>
    </div>
</div>
@endsection