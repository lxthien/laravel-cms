@extends('frontend.layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)

@section('breadcrumb')
<div class="bg-white border-b">
    <div class="container mx-auto px-4 py-3">
        <nav class="text-sm">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Trang Chủ</a>
            <span class="mx-2">/</span>
            <a href="{{ route('category.show', $post->category->slug) }}" 
               class="text-blue-600 hover:underline">
                {{ $post->category->name }}
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-600">{{ $post->title }}</span>
        </nav>
    </div>
</div>
@endsection

@section('content')
<article class="bg-white rounded-lg shadow p-8">
    <!-- Title -->
    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
    
    <!-- Meta -->
    <div class="flex items-center gap-4 text-sm text-gray-600 mb-6 pb-6 border-b">
        <span>Tác giả: <strong>{{ $post->user->name }}</strong></span>
        <span>•</span>
        <span>{{ $post->published_at->format('d/m/Y H:i') }}</span>
        <span>•</span>
        <span>{{ $post->view_count }} lượt xem</span>
    </div>
    
    <!-- Featured Image -->
    @if($post->featured_image)
    <img src="{{ asset('storage/' . $post->featured_image) }}" 
         alt="{{ $post->title }}"
         class="w-full h-auto rounded-lg mb-6">
    @endif
    
    <!-- Excerpt -->
    @if($post->excerpt)
    <div class="text-lg text-gray-700 font-medium mb-6 p-4 bg-gray-50 rounded border-l-4 border-blue-600">
        {{ $post->excerpt }}
    </div>
    @endif
    
    <!-- Content -->
    <div class="prose prose-lg max-w-none">
        {!! $post->content !!}
    </div>
    
    <!-- Tags -->
    @if($post->tags->count() > 0)
    <div class="mt-8 pt-8 border-t">
        <h3 class="font-bold mb-3">Tags:</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
            <a href="#" 
               class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm hover:bg-blue-200">
                #{{ $tag->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <h4 class="font-bold text-xl mb-2">Gửi bình luận</h4>
    <form method="POST" action="{{ route('comment.store', $post) }}" id="commentForm">
        @csrf
        @guest
            <input name="name" required placeholder="Tên" class="border rounded p-2 w-full mb-2">
            <input name="email" type="email" required placeholder="Email" class="border rounded p-2 w-full mb-2">
        @endguest
        <input type="hidden" name="parent_id" id="parent_id" value="">
        <textarea name="content" required class="border rounded p-2 w-full mb-2" rows="4" placeholder="Nhập nội dung..."></textarea>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Gửi bình luận</button>
    </form>
    <script>
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('parent_id').value = btn.getAttribute('data-id');
            window.scrollTo(0, document.getElementById('commentForm').offsetTop - 100);
        }
    });
    </script>

    <h3 class="text-lg font-bold mb-4">Bình luận</h3>
    @include('frontend.partials.comments', ['comments' => $comments])

</article>

<!-- Related Posts -->
@if($relatedPosts->count() > 0)
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Bài Viết Liên Quan</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($relatedPosts as $related)
        <article class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
            <h3 class="font-bold mb-2">
                <a href="{{ route('post.show', $related->slug) }}" 
                   class="hover:text-blue-600">
                    {{ $related->title }}
                </a>
            </h3>
            <p class="text-sm text-gray-600">{{ $related->published_at->format('d/m/Y') }}</p>
        </article>
        @endforeach
    </div>
</div>
@endif
@endsection