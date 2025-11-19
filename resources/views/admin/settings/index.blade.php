@extends('admin.layouts.app')

@section('title', 'Quản Lý Cấu Hình')

@section('content')
<h2 class="text-xl font-bold mb-4">Nhóm Cấu Hình</h2>
<ul class="list-disc pl-5">
    @foreach($settings as $group => $items)
    <li class="mb-2">
        <a href="{{ route('admin.settings.edit', $group) }}" class="text-blue-600 hover:underline capitalize">
            {{ $group }}
        </a>
    </li>
    @endforeach
</ul>
@endsection