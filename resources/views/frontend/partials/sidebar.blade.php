<!-- Latest Posts -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Bài Viết Mới Nhất</h3>
    <ul class="space-y-4">
        @foreach(\App\Models\Post::published()->latest()->take(5)->get() as $post)
        <li class="border-b pb-3">
            <a href="{{ route('post.show', $post->slug) }}" class="hover:text-blue-600">
                <div class="flex gap-3">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                         alt="{{ $post->title }}"
                         class="w-20 h-20 object-cover rounded">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm line-clamp-2">{{ $post->title }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $post->published_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </a>
        </li>
        @endforeach
    </ul>
</div>

<!-- Categories -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Danh Mục</h3>
    <ul class="space-y-2">
        @foreach(\App\Models\Category::withCount('posts')->where('status', 1)->orderBy('name')->get() as $category)
        <li>
            <a href="{{ route('category.show', $category->slug) }}" 
               class="flex justify-between items-center text-gray-700 hover:text-blue-600">
                <span>{{ $category->name }}</span>
                <span class="text-sm text-gray-500">({{ $category->posts_count }})</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>

<!-- Tags -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold mb-4">Tags</h3>
    <div class="flex flex-wrap gap-2">
        @foreach(\App\Models\Tag::withCount('posts')->orderBy('name')->get() as $tag)
        <a href="#"
           class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-blue-100 hover:text-blue-600">
            {{ $tag->name }}
        </a>
        @endforeach
    </div>
</div>
