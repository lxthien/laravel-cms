@extends('admin.layouts.app')
@section('title', 'Chi tiết liên hệ')
@section('content')
<div class="max-w-lg bg-white rounded shadow p-6">
    <h2 class="font-bold text-xl mb-4">Thông tin chi tiết liên hệ</h2>
    <p><b>Họ tên:</b> {{ $contact->name }}</p>
    <p><b>Email:</b> {{ $contact->email }}</p>
    <p><b>Phone:</b> {{ $contact->phone }}</p>
    <p><b>Gửi lúc:</b> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
    <p><b>Nội dung:</b></p>
    <div class="border-l-4 pl-3 bg-gray-50 mb-2">{{ $contact->message }}</div>
    <a href="{{ route('admin.contacts.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách</a>
</div>
@endsection