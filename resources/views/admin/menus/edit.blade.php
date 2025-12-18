@extends('admin.layouts.app')

@section('title', 'Menu Builder - ' . $menu->name)

@section('page-title', 'Menu Builder')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>

        <style>
            /* Nestable2 Core Layout Fix */
            .dd {
                position: relative;
                display: block;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .dd-list {
                display: block;
                position: relative;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .dd-list .dd-list {
                padding-left: 30px;
            }

            .dd-item,
            .dd-empty,
            .dd-placeholder {
                display: block;
                position: relative;
                margin: 0;
                padding: 0;
                min-height: 20px;
                line-height: 20px;
            }

            .dd-handle {
                display: flex !important;
                align-items: center !important;
                height: 48px !important;
                margin: 5px 0 !important;
                padding: 5px 15px !important;
                cursor: move;
                background: #fff !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                box-sizing: border-box !important;
            }

            .dd-item>button {
                display: none;
            }

            .dd-placeholder {
                margin: 5px 0;
                padding: 0;
                min-height: 48px;
                background: #f8fafc;
                border: 1px dashed #cbd5e1;
                border-radius: 8px;
            }

            /* Builder UI Refinements */
            .dd-handle:hover {
                border-color: #3b82f6 !important;
            }

            .dd-list .dd-list {
                padding-left: 40px;
                margin-top: 5px;
            }

            .accordion-toggle.active svg {
                transform: rotate(180deg);
            }

            .item-checkbox {
                width: 1.125rem;
                height: 1.125rem;
            }
        </style>

        <script>
            jQuery(document).ready(function ($) {
                // Initialize Nestable
                $('#nestable-menu').nestable({
                    maxDepth: 3,
                    callback: function (l, e) {
                        // Update order/structure if needed when dragging
                    }
                });

                // Accordion Logic
                $('.accordion-toggle').click(function () {
                    const target = $(this).data('target');
                    $(this).toggleClass('active');
                    $(target).slideToggle(200);
                });

                // Add to Menu (from Checkboxes)
                $('.add-to-menu').click(function () {
                    const panel = $(this).closest('div');
                    const checked = panel.find('.item-checkbox:checked');

                    checked.each(function () {
                        const data = $(this).data();
                        addMenuItemToStructure({
                            title: data.title,
                            url: null,
                            model_type: data.type,
                            model_id: data.id,
                            target: '_self',
                            icon: null,
                            css_class: null
                        });
                        $(this).prop('checked', false);
                    });

                    $('#empty-state').addClass('hidden');
                });

                // Add Custom Link
                $('.add-custom-link').click(function () {
                    const title = $('#custom-title').val();
                    const url = $('#custom-url').val();

                    if (!title) return alert('Vui lòng nhập tên hiển thị');

                    addMenuItemToStructure({
                        title: title,
                        url: url,
                        model_type: null,
                        model_id: null,
                        target: '_self',
                        icon: null,
                        css_class: null
                    });

                    $('#custom-title').val('');
                    $('#custom-url').val('http://');
                    $('#empty-state').addClass('hidden');
                });

                function addMenuItemToStructure(data) {
                    const id = 'new-' + Date.now() + Math.floor(Math.random() * 100);
                    const itemHtml = `
                            <li class="dd-item" 
                                data-id="${id}" 
                                data-title="${data.title}"
                                data-url="${data.url || ''}"
                                data-target="${data.target}"
                                data-icon="${data.icon || ''}"
                                data-css_class="${data.css_class || ''}"
                                data-model_type="${data.model_type || ''}"
                                data-model_id="${data.model_id || ''}">

                                <div class="flex items-center bg-white border border-gray-200 rounded-md shadow-sm mb-2 overflow-hidden group hover:border-blue-300 transition-colors">
                                    <div class="dd-handle h-12 flex-1 flex items-center px-4 cursor-move bg-white">
                                        <span class="text-gray-400 mr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z"/></svg>
                                        </span>
                                        <span class="text-sm font-semibold text-gray-700 truncate">${data.title}</span>
                                    </div>

                                    <div class="flex items-center px-4 py-2 bg-gray-50 border-l border-gray-100 h-12">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 mr-4">${data.model_type ? data.model_type.split('\\').pop() : 'Custom'}</span>
                                        <button type="button" class="text-gray-400 hover:text-blue-600 item-edit-toggle transition-colors p-1">
                                            <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="item-settings hidden bg-gray-50 border border-gray-100 rounded-md mb-2 p-5 space-y-4 shadow-inner">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tên hiển thị</label>
                                            <input type="text" class="w-full text-sm border-gray-200 rounded edit-title" value="${data.title}">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Icon (Font Awesome)</label>
                                            <input type="text" class="w-full text-sm border-gray-200 rounded edit-icon" value="${data.icon || ''}" placeholder="fas fa-home">
                                        </div>
                                    </div>

                                    ${!data.model_type ? `
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">URL</label>
                                        <input type="text" class="w-full text-sm border-gray-200 rounded edit-url" value="${data.url || ''}">
                                    </div>
                                    ` : ''}

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">CSS Classes</label>
                                            <input type="text" class="w-full text-sm border-gray-200 rounded edit-css_class" value="${data.css_class || ''}" placeholder="custom-class mb-2">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mở trong tab mới?</label>
                                            <select class="w-full text-sm border-gray-200 rounded edit-target">
                                                <option value="_self" ${data.target === '_self' ? 'selected' : ''}>Không (Hiện tại)</option>
                                                <option value="_blank" ${data.target === '_blank' ? 'selected' : ''}>Có (Tab mới)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                        <button type="button" class="text-red-500 text-xs font-bold uppercase tracking-widest hover:text-red-700 transition-colors item-remove">Xóa mục này</button>
                                        <span class="text-[10px] text-gray-400">Mới</span>
                                    </div>
                                </div>
                            </li>
                        `;

                    $('#nestable-menu > .dd-list').append(itemHtml);
                }

                // Toggle Settings
                $(document).on('click', '.item-edit-toggle', function () {
                    const item = $(this).closest('.dd-item');
                    item.find('.item-settings').first().slideToggle(200);
                    $(this).toggleClass('rotate-180');
                });

                // Remove Item
                $(document).on('click', '.item-remove', function () {
                    if (confirm('Bạn có chắc muốn xóa mục menu này? All children will also be deleted.')) {
                        $(this).closest('.dd-item').remove();
                        if ($('#nestable-menu .dd-item').length === 0) {
                            $('#empty-state').removeClass('hidden');
                        }
                    }
                });

                // Sync input values to data attributes
                $(document).on('input change', '.item-settings input, .item-settings select', function () {
                    const settings = $(this).closest('.item-settings');
                    const item = settings.closest('.dd-item');
                    const fieldName = $(this).attr('class').split('edit-')[1]; // title, icon, etc.
                    const newVal = $(this).val();

                    item.attr('data-' + fieldName, newVal);

                    // If title changed, update the display text too
                    if (fieldName === 'title') {
                        item.find('.dd-handle .text-sm').first().text(newVal);
                    }
                });

                // Save Menu
                $('#save-menu').click(function () {
                    const btn = $(this);
                    const originalHtml = btn.html();

                    btn.prop('disabled', true).html('<svg class="animate-spin w-4 h-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang lưu...');

                    // 1. First save the main menu metadata (Name, Location)
                    $.ajax({
                        url: "{{ route('admin.menus.update', $menu) }}",
                        method: 'PUT',
                        data: {
                            name: $('#menu-name').val(),
                            location: $('#menu-location').val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            // 2. Then save structure
                            const structure = $('#nestable-menu').nestable('serialize');

                            $.ajax({
                                url: "{{ route('admin.menus.update-structure', $menu) }}",
                                method: 'POST',
                                data: {
                                    items: structure,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    showToast(response.message, 'success');
                                    setTimeout(() => location.reload(), 1000);
                                },
                                error: function (xhr) {
                                    showToast('Lỗi khi lưu cấu trúc: ' + (xhr.responseJSON?.message || 'Unknown'), 'error');
                                    btn.prop('disabled', false).html(originalHtml);
                                }
                            });
                        },
                        error: function (xhr) {
                            showToast('Lỗi khi lưu thông tin menu: ' + (xhr.responseJSON?.message || 'Unknown'), 'error');
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection