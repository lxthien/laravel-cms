@if (!empty($breadcrumbs))
<nav class="text-sm text-gray-500 py-3">
    <ol class="list-none p-0 inline-flex">
        @foreach ($breadcrumbs as $i => $crumb)
            @if ($i < count($breadcrumbs) - 1)
                <li class="flex items-center">
                    <a href="{{ $crumb['url'] }}" class="hover:text-blue-600">{{ $crumb['title'] }}</a>
                    <span class="mx-2">/</span>
                </li>
            @else
                <li class="flex items-center text-gray-700 font-semibold">
                    {{ $crumb['title'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif