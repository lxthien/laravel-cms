@extends('frontend.layouts.app')

<x-seo-meta :title="$post->meta_title ?: $post->title" :description="$post->meta_description ?: Str::limit(strip_tags($post->content), 155)" :keywords="$post->meta_keywords" :canonical-url="url($post->full_path)"
    :image="$post->featured_image ? asset('storage/' . $post->featured_image) : null" :robots="$post->index ? 'index, follow' : 'noindex, nofollow'" type="article" :author="$post->author->name ?? config('app.name')"
    :published-time="$post->published_at?->toIso8601String()" :modified-time="$post->updated_at->toIso8601String()" />

@section('schema')
    {{-- Breadcrumb Schema --}}
    <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        {
                            "@type": "ListItem",
                            "position": {{ $index + 1 }},
                            "name": "{{ $breadcrumb['title'] }}",
                            "item": "{{ $breadcrumb['url'] ?: url()->current() }}"
                        }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ]
            }
        </script>
@endsection

@section('breadcrumb')
    <div class="max-w-7xl mx-auto px-4 mt-4">
        @include('frontend.partials._breadcrumb', compact('breadcrumbs'))
    </div>
@endsection

@section('content')
    <article class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Post Header --}}
        <header class="p-6 md:p-8 border-b border-slate-50">
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-slate-900 leading-tight mb-4">
                {{ $post->title }}
            </h1>

            {{-- Meta Info --}}
            <div class="flex flex-wrap items-center gap-y-3 gap-x-6 text-sm text-slate-500">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Tác giả: <strong class="text-slate-700">{{ $post->user->name }}</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $post->published_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <span>{{ number_format($post->view_count) }} lượt xem</span>
                </div>
            </div>
        </header>

        {{-- Featured Image --}}
        @if($post->featured_image)
            <div class="px-6 md:px-8">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                    class="w-full h-auto rounded-xl shadow-md my-6">
            </div>
        @endif

        {{-- Body Content --}}
        <div class="p-6 md:p-8 pt-0">
            {{-- Excerpt / Slogan box --}}
            @if($post->excerpt)
                <div
                    class="text-lg text-slate-700 font-medium mb-8 p-6 bg-slate-50 rounded-xl border-l-4 border-accent relative italic">
                    <svg class="absolute top-2 left-2 w-8 h-8 text-orange-200 opacity-30" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017C11.4647 12 11.017 11.5523 11.017 11V10C11.017 7.79086 12.8079 6 15.017 6H20.017C21.1216 6 22.017 6.89543 22.017 8V15C22.017 16.6569 20.6739 18 19.017 18H16.017L16.017 21H14.017ZM3.017 21L3.017 18C3.017 16.8954 3.91243 16 5.017 16H8.017C8.56928 16 9.017 15.5523 9.017 15V9C9.017 8.44772 8.56928 8 8.017 8H4.017C3.46472 8 3.017 8.44772 3.017 9V11C3.017 11.5523 2.56928 12 2.017 12H1.017C0.464718 12 0.017 11.5523 0.017 11V10C0.017 7.79086 1.80787 6 4.017 6H9.017C10.1216 6 11.017 6.89543 11.017 8V15C11.017 16.6569 9.67388 18 8.017 18H5.017L5.017 21H3.017Z">
                        </path>
                    </svg>
                    <span class="relative z-10">{{ $post->excerpt }}</span>
                </div>
            @endif

            {{-- Main Prose Content --}}
            <div class="landing-prose !p-0">
                {!! $post->content !!}
            </div>

            {{-- Tags --}}
            @if($post->tags->count() > 0)
                <div class="mt-10 pt-6 border-t border-slate-100 italic">
                    <span class="text-sm text-slate-400 mr-2">Tags:</span>
                    <div class="inline-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="#" class="text-xs font-semibold text-slate-500 hover:text-accent transition-colors">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Comment Section --}}
        <section class="bg-slate-50 p-6 md:p-8 border-t border-slate-100">
            <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                Bình luận & Thảo luận
            </h3>

            {{-- Comment Form --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 mb-8">
                <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Gửi bình luận của bạn</h4>
                <form method="POST" action="{{ route('comment.store', $post) }}" id="commentForm" class="space-y-4">
                    @csrf
                    @guest
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <input name="name" required placeholder="Họ và tên *" class="form-input">
                            <input name="email" type="email" required placeholder="Email (bảo mật) *" class="form-input">
                        </div>
                    @endguest
                    <input type="hidden" name="parent_id" id="parent_id" value="">
                    <textarea name="content" required class="form-textarea" rows="3"
                        placeholder="Chia sẻ ý kiến của bạn về bài viết này..."></textarea>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-accent hover:bg-orange-600 text-white font-bold px-6 py-2.5 rounded-lg transition-all shadow-md shadow-orange-100 active:scale-95">
                            Gửi bình luận
                        </button>
                    </div>
                </form>
            </div>

            {{-- Comment List --}}
            <div class="space-y-4">
                @include('frontend.partials.comments', ['comments' => $comments])
            </div>
        </section>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->count() > 0)
        <div class="mt-12">
            <div class="section-heading-custom">
                <h2 class="heading-title">
                    <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    Bài viết liên quan
                </h2>
                <span class="heading-arrow">»</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                @foreach($relatedPosts as $related)
                    {{-- Mini service-card version could be used or standard one --}}
                    <article class="service-card">
                        @if($related->featured_image)
                            <div class="card-img">
                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}">
                                <div class="card-overlay">
                                    <a href="{{ url($related->full_path) }}" class="view-btn">Đọc tiếp</a>
                                </div>
                            </div>
                        @endif

                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="{{ url($related->full_path) }}">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            <div class="text-xs text-slate-400 flex items-center gap-1 mt-auto pt-3">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $related->published_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
@endsection