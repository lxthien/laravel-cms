@extends('frontend.layouts.app')

<x-seo-meta :title="$page->meta_title ?: $page->title" :description="$page->meta_description ?: Str::limit(strip_tags($page->content), 155)" :keywords="$page->meta_keywords" :canonical-url="url($page->slug)"
    :image="$page->featured_image ? asset('storage/' . $page->featured_image) : null" robots="index, follow"
    type="website" />

@section('breadcrumb')
    @include('frontend.partials._breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('content')
    <!-- Page Content -->
    <article class="prose lg:prose-xl mx-auto bg-white p-6 md:p-8 shadow-sm rounded-lg">
        <h1 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">{{ $page->title }}</h1>

        @if($page->featured_image)
            <div class="mb-8">
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                    class="w-full h-auto rounded-lg shadow-md">
            </div>
        @endif

        <div class="content">
            {!! $page->content !!}
        </div>
    </article>
@endsection