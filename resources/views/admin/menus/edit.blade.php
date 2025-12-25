@extends('admin.layouts.app')

@section('title', 'Menu Builder - ' . $menu->name)

@section('page-title', 'Menu Builder')

@section('content')
    <div id="menu-builder-container" class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar: Add Items --}}
        <div class="w-full lg:w-1/3 space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-bold text-gray-700">Thêm mục menu</h3>
                </div>
                <div class="p-4 space-y-2" id="menu-items-accordion">
                    {{-- Pages Accordion --}}
                    <div class="border border-gray-100 rounded-md overflow-hidden">
                        <button
                            class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-left text-sm font-semibold flex justify-between items-center transition-colors accordion-toggle"
                            data-target="#pages-panel">
                            Trang mới nhất
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="pages-panel" class="hidden p-4 space-y-3">
                            <div class="max-h-48 overflow-y-auto space-y-2">
                                @foreach($pages as $page)
                                    <label class="flex items-center gap-2 cursor-pointer p-1 hover:bg-blue-50 rounded">
                                        <input type="checkbox" class="rounded text-blue-600 item-checkbox"
                                            data-title="{{ $page->title }}" data-type="App\Models\Page"
                                            data-id="{{ $page->id }}">
                                        <span class="text-sm text-gray-700">{{ $page->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="button"
                                class="w-full py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded text-sm font-bold hover:bg-blue-50 transition-all add-to-menu"
                                data-type="page">
                                Thêm vào menu
                            </button>
                        </div>
                    </div>

                    {{-- Posts Accordion --}}
                    <div class="border border-gray-100 rounded-md overflow-hidden">
                        <button
                            class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-left text-sm font-semibold flex justify-between items-center transition-colors accordion-toggle"
                            data-target="#posts-panel">
                            Bài viết mới nhất
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="posts-panel" class="hidden p-4 space-y-3">
                            <div class="max-h-48 overflow-y-auto space-y-2">
                                @foreach($posts as $post)
                                    <label class="flex items-center gap-2 cursor-pointer p-1 hover:bg-blue-50 rounded">
                                        <input type="checkbox" class="rounded text-blue-600 item-checkbox"
                                            data-title="{{ $post->title }}" data-type="App\Models\Post"
                                            data-id="{{ $post->id }}">
                                        <span class="text-sm text-gray-700">{{ $post->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="button"
                                class="w-full py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded text-sm font-bold hover:bg-blue-50 transition-all add-to-menu"
                                data-type="post">
                                Thêm vào menu
                            </button>
                        </div>
                    </div>

                    {{-- Categories Accordion --}}
                    <div class="border border-gray-100 rounded-md overflow-hidden">
                        <button
                            class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-left text-sm font-semibold flex justify-between items-center transition-colors accordion-toggle"
                            data-target="#categories-panel">
                            Danh mục
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="categories-panel" class="hidden p-4 space-y-3">
                            <div class="max-h-48 overflow-y-auto space-y-2">
                                @foreach($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer p-1 hover:bg-blue-50 rounded">
                                        <input type="checkbox" class="rounded text-blue-600 item-checkbox"
                                            data-title="{{ $category->name }}" data-type="App\Models\Category"
                                            data-id="{{ $category->id }}">
                                        <span class="text-sm text-gray-700">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="button"
                                class="w-full py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded text-sm font-bold hover:bg-blue-50 transition-all add-to-menu"
                                data-type="category">
                                Thêm vào menu
                            </button>
                        </div>
                    </div>

                    {{-- Custom Link Accordion --}}
                    <div class="border border-gray-100 rounded-md overflow-hidden">
                        <button
                            class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-left text-sm font-semibold flex justify-between items-center transition-colors accordion-toggle"
                            data-target="#custom-links-panel">
                            Liên kết tùy chỉnh (URL)
                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="custom-links-panel" class="hidden p-4 space-y-4">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-tight">URL</label>
                                <input type="text" id="custom-url" value="http://"
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm outline-none focus:border-blue-500">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-tight">Tên hiển
                                    thị</label>
                                <input type="text" id="custom-title"
                                    class="w-full border border-gray-200 rounded px-3 py-2 text-sm outline-none focus:border-blue-500">
                            </div>
                            <button type="button"
                                class="w-full py-2 px-4 bg-white border border-blue-600 text-blue-600 rounded text-sm font-bold hover:bg-blue-50 transition-all add-custom-link">
                                Thêm vào menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Builder Area --}}
        <div class="w-full lg:w-2/3 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Cấu trúc Menu</h3>
                    <span class="text-xs text-gray-400">Kéo thả để sắp xếp, lồng tối đa 3 cấp</span>
                </div>

                <div class="p-6">
                    {{-- Menu Meta Settings --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-8 border-b border-gray-100">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-tight">Tên Menu</label>
                            <input type="text" id="menu-name" value="{{ $menu->name }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-tight">Vị trí
                                (Location)</label>
                            <select id="menu-location"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all appearance-none">
                                <option value="header" {{ $menu->location === 'header' ? 'selected' : '' }}>Header (Đầu trang)
                                </option>
                                <option value="footer" {{ $menu->location === 'footer' ? 'selected' : '' }}>Footer (Chân
                                    trang)</option>
                                <option value="sidebar" {{ $menu->location === 'sidebar' ? 'selected' : '' }}>Sidebar (Thanh
                                    bên)</option>
                            </select>
                        </div>
                    </div>

                    <div class="dd" id="nestable-menu">
                        <ol class="dd-list">
                            @foreach($menuItems as $item)
                                @include('admin.menus.partials.menu_item', ['item' => $item])
                            @endforeach
                        </ol>
                    </div>

                    <div id="empty-state"
                        class="{{ $menuItems->isEmpty() ? '' : 'hidden' }} py-12 text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-xl">
                        <svg class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <p class="text-sm font-medium">Menu hiện đang trống. Hãy thêm các mục từ bên trái.</p>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                    <button type="button" onclick="window.history.back()"
                        class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">Hủy</button>
                    <button type="button" id="save-menu"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Lưu Menu
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- jQuery must be loaded before nestable2 --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>

        {{-- Menu Builder Configuration via data attributes --}}
        <script>
            // Set menu builder configuration on container
            jQuery(function ($) {
                $('#menu-builder-container').attr({
                    'data-menu-id': '{{ $menu->id }}',
                    'data-update-url': '{{ route('admin.menus.update', $menu) }}',
                    'data-structure-url': '{{ route('admin.menus.update-structure', $menu) }}'
                });
                // Re-initialize after data attributes are set
                if (typeof initMenuBuilder === 'function') {
                    initMenuBuilder();
                }
            });
        </script>
    @endpush
@endsection