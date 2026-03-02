@extends('frontend.layouts.app')

<x-seo-meta :title="$category->meta_title ?: $category->name" :description="$category->meta_description ?: 'Danh mục ' . $category->name" :keywords="$category->meta_keywords" :canonical-url="url($category->full_path)"
    :image="$category->image ? asset('storage/' . $category->image) : null" robots="index, follow" type="website" />

@section('breadcrumb')
    <div class="max-w-7xl mx-auto px-4 mt-4">
        {{-- Include Breadcrumb --}}
        @include('frontend.partials._breadcrumb', ['breadcrumbs' => $breadcrumbs])
    </div>
@endsection

@section('content')
    {{-- Custom Section Heading for Category --}}
    <div class="section-heading-custom">
        <h1 class="heading-title">
            <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
            {{ $category->name }}
        </h1>
        <span class="heading-arrow">»</span>

        {{-- Optional: Subcategories could be shown as heading-tabs here later if needed --}}
        <div class="heading-tabs"></div>
    </div>

    {{-- Post Grid reusing .service-card UI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <article class="service-card">
                @if($post->featured_image)
                    <div class="card-img">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                        <div class="card-overlay">
                            <a href="{{ url($post->full_path) }}" class="view-btn">Xem chi tiết</a>
                        </div>
                    </div>
                @endif

                <div class="card-content">
                    <h3 class="card-title">
                        <a href="{{ url($post->full_path) }}" aria-label="{{ $post->title }}">
                            {{ $post->title }}
                        </a>
                    </h3>

                    @if($post->excerpt)
                        <p class="card-desc">
                            {{ $post->excerpt }}
                        </p>
                    @endif

                    {{-- Optional published date (aligned to bottom if needed) --}}
                    <div class="text-sm text-gray-400 mt-auto pt-4 border-t border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $post->published_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-slate-50 rounded-lg border border-slate-100">
                <svg class="w-12 h-12 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Chưa có bài viết nào trong danh mục này.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10 flex justify-center">
        {{ $posts->links() }}
    </div>
@endsection