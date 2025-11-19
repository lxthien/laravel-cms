@if($items->count())
<ul class="flex gap-6">
    @foreach($items as $item)
        <li>
            <a href="{{ $item->url }}" target="{{ $item->target ?: '_self' }}" class="text-gray-700 hover:text-blue-600 font-medium">
                @if($item->icon)
                    <img src="{{ asset('storage/' . $item->icon) }}" alt="{{ $item->title }}" class="inline mr-1 h-4 w-4">
                @endif
                {{ $item->title }}
            </a>
            @if($item->children && $item->children->count())
                <x-menu :items="$item->children" />
            @endif
        </li>
    @endforeach
</ul>
@endif