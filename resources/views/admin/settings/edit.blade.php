@extends('admin.layouts.app')

@section('title', 'Cấu Hình: ' . $group)

@section('content')
<h2 class="text-xl font-bold mb-6 capitalize">Cấu Hình nhóm: {{ $group }}</h2>

<form action="{{ route('admin.settings.update', $group) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        @foreach ($settings as $setting)
            <div>
                <label for="{{ $setting->key }}" class="block font-semibold mb-1 capitalize">
                    {{ str_replace('_', ' ', $setting->key) }}
                </label>

                @if($setting->type == 'boolean')
                    <input type="checkbox" name="{{ $setting->key }}" id="{{ $setting->key }}" value="1" 
                        {{ $setting->value == '1' ? 'checked' : '' }}>
                @elseif($setting->type == 'textarea')
                    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="4"
                              class="w-full border rounded px-3 py-2">{{ old($setting->key, $setting->value) }}</textarea>
                @elseif($setting->type == 'image')
                    @if($setting->value)
                        <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="mb-2 max-w-xs rounded" />
                    @endif
                    <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}">
                @else
                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}"
                           value="{{ old($setting->key, $setting->value) }}"
                           class="w-full border rounded px-3 py-2">
                @endif
            </div>
        @endforeach
    </div>

    <button type="submit" class="mt-6 bg-blue-500 text-white rounded px-6 py-2 hover:bg-blue-600">
        Lưu Cấu Hình
    </button>
    <a href="{{ route('admin.settings.index') }}" class="ml-4 text-gray-500 hover:underline">Quay lại</a>
</form>
@endsection