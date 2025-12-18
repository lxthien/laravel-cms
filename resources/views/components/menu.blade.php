@if($items->count())
    <ul class="{{ $attributes->get('class', 'flex flex-wrap items-center gap-x-8') }}">
        @foreach($items as $item)
            @php
                $hasChildren = $item->children->count() > 0;
                $isActive = $item->isActive();
            @endphp
            <li class="relative group">
                <a href="{{ $item->getUrl() }}" target="{{ $item->target ?: '_self' }}"
                    class="flex items-center gap-2 py-6 text-sm font-bold transition-all {{ $isActive ? 'text-blue-600' : 'text-gray-700 hover:text-blue-600' }}">

                    @if($item->icon)
                        <i class="{{ $item->icon }}"></i>
                    @endif

                    <span class="uppercase tracking-wide">{{ $item->title }}</span>

                    @if($hasChildren)
                        <svg class="w-4 h-4 opacity-50 transition-transform group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                </a>

                @if($hasChildren)
                    <ul
                        class="absolute left-0 top-full hidden group-hover:block bg-white shadow-2xl border border-gray-100 rounded-xl min-w-[220px] z-50 py-3 animate-in fade-in slide-in-from-top-2 duration-200">
                        @foreach($item->children as $child)
                            @include('components.menu-item-recursive', ['item' => $child, 'depth' => 1])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif