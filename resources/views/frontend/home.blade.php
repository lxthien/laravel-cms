@extends('frontend.layouts.app')

@section('title', setting('page_title'))

<x-seo-meta title="{{ setting('page_title') }}" description="{{ setting('page_description') }}"
    keywords="{{ setting('page_keywords') }}" robots="index, follow" type="website" />

@section('content')
    <!-- Hero -->
    <section class="relative bg-cover bg-center rounded-lg overflow-hidden mb-8"
        style="background-image: linear-gradient(rgba(15,23,42,0.45), rgba(15,23,42,0.25)), url('{{ asset('images/hero-project.jpg') }}');">
        <div class="max-w-7xl mx-auto px-4 py-20 text-white">
            <h1 class="text-3xl sm:text-4xl font-heading font-bold mb-4">Xây dựng vững chắc, kiến tạo tương lai</h1>
            <p class="max-w-2xl text-slate-100 mb-6">Chúng tôi chuyên thiết kế và thi công các công trình dân dụng & công
                nghiệp với tiêu chuẩn chất lượng quốc tế.</p>
            <div class="flex gap-3">
                <a href="{{ route('contact') }}" class="bg-accent text-black font-semibold px-5 py-3 rounded-md shadow">Yêu
                    cầu báo giá</a>
                <a href="#" class="border border-white/40 text-white px-5 py-3 rounded-md">Xem dự án</a>
            </div>
        </div>
    </section>
    <!-- Featured Posts -->
    <div class="mb-8">
        <h2 class="text-2xl font-heading font-bold mb-4 text-primary">Bài Viết Nổi Bật</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredPosts as $post)
                <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    @if($post->featured_image)
                        <a href="{{ url($post->full_path) }}">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                class="w-full h-48 object-cover">
                        </a>
                    @endif

                    <div class="p-4">
                        <div class="text-sm text-slate-500 mb-2">
                            <a href="{{ url($post->primaryCategory()->full_path) }}" class="text-accent hover:underline">
                                {{ $post->primaryCategory()->name }}
                            </a>
                            <span class="mx-2">•</span>
                            <span>{{ $post->published_at->format('d/m/Y') }}</span>
                        </div>

                        <h3 class="text-xl font-heading font-bold mb-2">
                            <a href="{{ url($post->full_path) }}" class="hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h3>

                        @if($post->excerpt)
                            <p class="text-gray-600 text-sm line-clamp-3">
                                {{ $post->excerpt }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    <!-- Latest Posts -->
    <div>
        <h2 class="text-2xl font-heading font-bold mb-4 text-primary">Bài Viết Mới Nhất</h2>
        <div class="space-y-4">
            @foreach($latestPosts as $post)
                <article class="bg-white rounded-lg shadow p-4 flex gap-4 hover:shadow-lg transition">
                    @if($post->featured_image)
                        <a href="{{ url($post->full_path) }}" class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                class="w-32 h-32 object-cover rounded">
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

                        <h3 class="text-lg font-bold mb-2">
                            <a href="{{ url($post->full_path) }}" class="hover:text-blue-600">
                                {{ $post->title }}
                            </a>
                        </h3>

                        @if($post->excerpt)
                            <p class="text-gray-600 text-sm line-clamp-2">
                                {{ $post->excerpt }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection