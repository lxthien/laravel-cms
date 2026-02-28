@if($items->count())
    <ul class="{{ $attributes->get('class', 'nav-menu') }}">
        @foreach($items as $item)
            @php
                $hasChildren = $item->children->count() > 0;
                $isActive = $item->isActive();
            @endphp
            <li class="nav-item">
                <a href="{{ $item->getUrl() }}" target="{{ $item->target ?: '_self' }}"
                    class="nav-link {{ $isActive ? 'is-active' : '' }}">

                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif

                    <span class="nav-text">{{ $item->title }}</span>

                    @if($hasChildren)
                        <svg class="nav-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                </a>

                @if($hasChildren)
                    <ul class="nav-dropdown">
                        @foreach($item->children as $child)
                            @include('components.menu-item-recursive', ['item' => $child, 'depth' => 1])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif