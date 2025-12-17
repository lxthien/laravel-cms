@extends('frontend.layouts.app')

<x-seo-meta
    :title="$page->meta_title ?: $page->title"
    :description="$page->meta_description ?: Str::limit(strip_tags($page->content), 155)"
    :keywords="$page->meta_keywords"
    :canonical-url="url($page->slug)"
    :image="$page->featured_image ? asset('storage/' . $page->featured_image) : null"
    robots="index, follow"
    type="website"
/>

@section('content')
    <!-- Breadcrumbs -->
    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="inline-flex items-center">
                            @if(!$loop->last)
                                <a href="{{ $breadcrumb['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    {{ $breadcrumb['title'] }}
                                </a>
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            @else
                                <span class="text-sm font-medium text-gray-500">{{ $breadcrumb['title'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container mx-auto px-4 py-8">
        <article class="prose lg:prose-xl mx-auto bg-white p-6 md:p-8 shadow-sm rounded-lg">
            <h1 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">{{ $page->title }}</h1>
            
            @if($page->featured_image)
                <div class="mb-8">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto rounded-lg shadow-md">
                </div>
            @endif

            <div class="content">
                {!! $page->content !!}
            </div>
        </article>
    </div>
@endsection
