@php
    $hasChildren = $item->children->count() > 0;
    $isActive = $item->isActive();
    $depth = $depth ?? 0;
@endphp

<li class="group/item {{ $hasChildren ? 'relative' : '' }} {{ $item->css_class }}">
    <a href="{{ $item->getUrl() }}" target="{{ $item->target ?: '_self' }}"
        class="flex items-center justify-between gap-2 px-4 py-2 text-sm transition-colors {{ $isActive ? 'text-blue-600 bg-blue-50/50' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">

        <div class="flex items-center gap-2">
            @if($item->icon)
                <i class="{{ $item->icon }} opacity-70"></i>
            @endif
            <span>{{ $item->title }}</span>
        </div>

        @if($hasChildren)
            <svg class="w-4 h-4 opacity-50 transition-transform group-hover/item:-rotate-90" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif
    </a>

    @if($hasChildren)
        <ul
            class="absolute left-full top-0 ml-px hidden group-hover/item:block bg-white shadow-xl border border-gray-100 rounded-lg min-w-[200px] z-[60] py-2 animate-in fade-in slide-in-from-left-2 duration-200">
            @foreach($item->children as $child)
                @include('components.menu-item-recursive', ['item' => $child, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>