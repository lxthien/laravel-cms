@extends('frontend.layouts.app')

@section('title', 'Tìm kiếm: ' . $query)
@section('meta_description', 'Kết quả tìm kiếm cho: ' . $query)

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">Kết quả tìm kiếm</h1>
        <p class="text-gray-600">
            Tìm thấy <strong>{{ $posts->total() }}</strong> kết quả cho: "<strong>{{ $query }}</strong>"
        </p>
    </div>

    <!-- Search Box -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('search') }}" method="GET">
            <div class="flex gap-2">
                <input type="text" name="q" value="{{ $query }}" placeholder="Nhập từ khóa tìm kiếm..."
                    class="flex-1 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if($posts->count() > 0)
        <div class="space-y-6">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                    <div class="flex gap-4">
                        @if($post->featured_image)
                            <a href="{{ url($post->full_path) }}" class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                    class="w-48 h-32 object-cover rounded">
                            </a>
                        @endif

                        <div class="flex-1">
                            <div class="text-sm text-gray-500 mb-2">
                                <a href="{{ url($post->primaryCategory()->full_path) }}" class="text-blue-600 hover:underline">
                                    {{ $post->primaryCategory()->name }}
                                </a>
                                <span class="mx-2">•</span>
                                <span>{{ $post->published_at->format('d/m/Y') }}</span>
                            </div>

                            <h2 class="text-2xl font-bold mb-3">
                                <a href="{{ url($post->full_path) }}" class="hover:text-blue-600">
                                    {!! highlightSearchTerm($post->title, $query) !!}
                                </a>
                            </h2>

                            @if($post->excerpt)
                                <p class="text-gray-600 mb-3">
                                    {!! highlightSearchTerm(Str::limit($post->excerpt, 200), $query) !!}
                                </p>
                            @endif

                            <a href="{{ url($post->full_path) }}" class="text-blue-600 hover:underline font-semibold">
                                Đọc thêm →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->appends(['q' => $query])->links() }}
        </div>

    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy kết quả</h3>
            <p class="text-gray-500 mb-6">Vui lòng thử lại với từ khóa khác</p>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline font-semibold">
                ← Quay lại trang chủ
            </a>
        </div>
    @endif
@endsection