@extends('admin.layouts.app')

@section('title', 'Cấu Hình Hệ Thống')
@section('page-title', 'Cấu Hình Hệ Thống')

@section('content')
    <div class="flex flex-col md:flex-row gap-6">
        {{-- Sidebar Groups --}}
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 bg-gray-50 border-b">
                    <h3 class="font-bold text-gray-700">Nhóm Cấu Hình</h3>
                </div>
                <nav class="flex flex-col p-2 space-y-1">
                    @foreach($groups as $g)
                                <a href="{{ route('admin.settings.edit', $g) }}" class="px-4 py-2 rounded-md font-medium capitalize transition-colors
                                                                                                                                                                                                                              {{ $group === $g
                        ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600'
                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    {{ $g }}
                                </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Main Content Form --}}
        <div class="w-full md:w-3/4">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-6 capitalize pb-4 border-b flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Thiết lập {{ $group }}
                </h2>

                <form action="{{ route('admin.settings.update', $group) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        @foreach ($settings as $setting)
                            <div class="border-b last:border-0 pb-6 last:pb-0">
                                <label for="{{ $setting->key }}" class="block font-semibold mb-2 capitalize text-gray-700">
                                    {{ str_replace('_', ' ', $setting->key) }}
                                </label>

                                @if($setting->type == 'boolean')
                                    {{-- Toggle Switch UI --}}
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="{{ $setting->key }}" id="{{ $setting->key }}" value="1"
                                            class="sr-only peer" {{ $setting->value == '1' ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                        <span
                                            class="ml-3 text-sm font-medium text-gray-900">{{ $setting->value == '1' ? 'Bật' : 'Tắt' }}</span>
                                    </label>

                                @elseif($setting->type == 'textarea')
                                    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="4"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">{{ old($setting->key, $setting->value) }}</textarea>

                                @elseif($setting->type == 'image')
                                    <div class="flex items-start gap-4">
                                        @if($setting->value)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}"
                                                    class="h-24 w-auto rounded border shadow-sm object-cover" />
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition rounded">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}" accept="image/*"
                                                class="block w-full text-sm text-gray-500
                                                                                                                                                                                                      file:mr-4 file:py-2 file:px-4
                                                                                                                                                                                                      file:rounded-full file:border-0
                                                                                                                                                                                                      file:text-sm file:font-semibold
                                                                                                                                                                                                      file:bg-blue-50 file:text-blue-700
                                                                                                                                                                                                      hover:file:bg-blue-100 cursor-pointer">
                                            <p class="mt-1 text-xs text-gray-500">Cho phép: jpg, jpeg, png, gif.</p>
                                        </div>
                                    </div>

                                @else
                                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}"
                                        value="{{ old($setting->key, $setting->value) }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                @endif

                                {{-- Optional: Description/Help text could go here if added to DB --}}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-4 border-t flex items-center justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            Lưu Cấu Hình
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection