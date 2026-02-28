@php
    $hasChildren = $item->children->count() > 0;
    $isActive = $item->isActive();
    $depth = $depth ?? 0;
@endphp

<li class="nav-dropdown-item {{ $hasChildren ? 'has-children' : '' }} {{ $item->css_class }}">
    <a href="{{ $item->getUrl() }}" target="{{ $item->target ?: '_self' }}"
        class="nav-dropdown-link {{ $isActive ? 'is-active' : '' }}">

        <div class="nav-dropdown-title">
            @if($item->icon)
                <i class="{{ $item->icon }} nav-dropdown-icon"></i>
            @endif
            <span>{{ $item->title }}</span>
        </div>

        @if($hasChildren)
            <svg class="nav-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif
    </a>

    @if($hasChildren)
        <ul class="nav-dropdown-submenu">
            @foreach($item->children as $child)
                @include('components.menu-item-recursive', ['item' => $child, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>