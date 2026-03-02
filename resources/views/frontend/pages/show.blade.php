@extends('frontend.layouts.app')

<x-seo-meta :title="$page->meta_title ?: $page->title" :description="$page->meta_description ?: Str::limit(strip_tags($page->content), 155)" :keywords="$page->meta_keywords" :canonical-url="url($page->slug)"
    :image="$page->featured_image ? asset('storage/' . $page->featured_image) : null" robots="index, follow"
    type="website" />

@push('body-class', 'layout-landing')
@section('full-width', true)

@section('breadcrumb')
    <div class="max-w-[1200px] mx-auto px-4 mt-4">
        @include('frontend.partials._breadcrumb', ['breadcrumbs' => $breadcrumbs])
    </div>
@endsection

@section('content')
    <!-- Landing Page Structure (Max width 1200px equivalent to max-w-7xl) -->
    <div class="landing-container">
        <!-- Hero Title Section -->
        <header class="landing-hero">
            <h1 class="landing-title">{{ $page->title }}</h1>
        </header>

        @if($page->featured_image)
            <div class="mb-4 text-center">
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                    class="mx-auto rounded-lg shadow-md max-h-[500px] object-cover">
            </div>
        @endif

        <!-- Prose Content Styled by _landing.scss -->
        <article class="landing-prose">
            {!! $page->content !!}
        </article>
    </div>
@endsection