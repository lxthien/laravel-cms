@extends('frontend.layouts.app')
@section('title', 'Liên hệ')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Thông tin liên hệ</h2>
    <ul class="mb-6 text-gray-700">
        <li><strong>Địa chỉ:</strong> {{ setting('contact_address') }}</li>
        <li><strong>Email:</strong> <a href="mailto:{{ setting('contact_email') }}" class="text-blue-600">{{ setting('contact_email') }}</a></li>
        <li><strong>Hotline:</strong> <a href="tel:{{ setting('contact_phone') }}" class="text-blue-600">{{ setting('contact_phone') }}</a></li>
        <li><strong>Website:</strong> <a href="{{ url('/') }}" class="text-blue-600">{{ url('/') }}</a></li>
    </ul>
</div>

@if(session('success'))
    <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-5">{{ session('success') }}</div>
@endif

<h2 class="text-xl font-bold mb-2">Gửi thông tin liên hệ</h2>
<form method="POST" action="{{ route('contact.submit') }}" class="bg-white p-6 rounded shadow space-y-4 max-w-md">
    @csrf
    <div>
        <label>Tên *</label>
        <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
        @error('name')<small class="text-red-500">{{ $message }}</small>@enderror
    </div>
    <div>
        <label>Email *</label>
        <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
        @error('email')<small class="text-red-500">{{ $message }}</small>@enderror
    </div>
    <div>
        <label>Điện thoại *</label>
        <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}">
        @error('phone')<small class="text-red-500">{{ $message }}</small>@enderror
    </div>
    <div>
        <label>Nội dung *</label>
        <textarea name="message" class="w-full border rounded px-3 py-2" rows="5" required>{{ old('message') }}</textarea>
        @error('message')<small class="text-red-500">{{ $message }}</small>@enderror
    </div>
    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Gửi liên hệ</button>
</form>
@endsection
