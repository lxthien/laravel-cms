@extends('frontend.layouts.app')

@section('title', $category->meta_title ?: $category->name)
@section('meta_description', $category->meta_description ?: $category->description)

@section('breadcrumb')
    {{-- Include Breadcrumb --}}
    @include('frontend.partials._breadcrumb', ['breadcrumbs' => $breadcrumbs])
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($posts as $post)
    <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
        @if($post->featured_image)
        <a href="{{ url($post->full_path) }}">
            <img src="{{ asset('storage/' . $post->featured_image) }}" 
                 alt="{{ $post->title }}"
                 class="w-full h-48 object-cover">
        </a>
        @endif
        
        <div class="p-4">
            <div class="text-sm text-gray-500 mb-2">
                {{ $post->published_at->format('d/m/Y') }}
            </div>
            
            <h3 class="text-xl font-bold mb-2">
                <a href="{{ url($post->full_path) }}" 
                   class="hover:text-blue-600">
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
    @empty
    <p class="col-span-2 text-center text-gray-500">Chưa có bài viết nào trong danh mục này.</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $posts->links() }}
</div>
@endsection