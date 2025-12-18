<li class="dd-item" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-url="{{ $item->url }}"
    data-target="{{ $item->target }}" data-icon="{{ $item->icon }}" data-css_class="{{ $item->css_class }}"
    data-model_type="{{ $item->model_type }}" data-model_id="{{ $item->model_id }}">

    <div
        class="flex items-center bg-white border border-gray-200 rounded-md shadow-sm mb-2 overflow-hidden group hover:border-blue-300 transition-colors">
        {{-- Handle area (Clickable for dragging) --}}
        <div class="dd-handle h-12 flex-1 flex items-center px-4 cursor-move bg-white">
            <span class="text-gray-400 mr-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z" />
                </svg>
            </span>
            <span class="text-sm font-semibold text-gray-700 truncate">{{ $item->title }}</span>
        </div>

        {{-- Type and Toggle area (Not draggable) --}}
        <div class="flex items-center px-4 py-2 bg-gray-50 border-l border-gray-100 h-12">
            <span
                class="text-[10px] uppercase font-bold text-gray-400 mr-4">{{ $item->model_type ? last(explode('\\', $item->model_type)) : 'Custom' }}</span>
            <button type="button" class="text-gray-400 hover:text-blue-600 item-edit-toggle transition-colors p-1">
                <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="item-settings hidden bg-gray-50 border border-gray-100 rounded-md mb-2 p-5 space-y-4 shadow-inner">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tên hiển thị</label>
                <input type="text" class="w-full text-sm border-gray-200 rounded edit-title" value="{{ $item->title }}">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Icon (Font Awesome)</label>
                <input type="text" class="w-full text-sm border-gray-200 rounded edit-icon" value="{{ $item->icon }}"
                    placeholder="fas fa-home">
            </div>
        </div>

        @if(!$item->model_type)
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">URL</label>
                <input type="text" class="w-full text-sm border-gray-200 rounded edit-url" value="{{ $item->url }}">
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">CSS Classes</label>
                <input type="text" class="w-full text-sm border-gray-200 rounded edit-css_class"
                    value="{{ $item->css_class }}" placeholder="custom-class mb-2">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mở trong tab mới?</label>
                <select class="w-full text-sm border-gray-200 rounded edit-target">
                    <option value="_self" {{ $item->target === '_self' ? 'selected' : '' }}>Không (Hiện tại)</option>
                    <option value="_blank" {{ $item->target === '_blank' ? 'selected' : '' }}>Có (Tab mới)</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center pt-2 border-t border-gray-200">
            <button type="button"
                class="text-red-500 text-xs font-bold uppercase tracking-widest hover:text-red-700 transition-colors item-remove">Xóa
                mục này</button>
            <span class="text-[10px] text-gray-400">ID: {{ $item->id }}</span>
        </div>
    </div>

    @if($item->children->isNotEmpty())
        <ol class="dd-list">
            @foreach($item->children as $child)
                @include('admin.menus.partials.menu_item', ['item' => $child])
            @endforeach
        </ol>
    @endif
</li>