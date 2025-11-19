@foreach($comments as $comment)
    <div class="mb-4 border-l-2 pl-3">
        <div class="flex gap-2 mb-1">
            <strong>{{ $comment->user ? $comment->user->name : $comment->name }}</strong>
            <span class="text-gray-400 text-xs">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="mb-1">{{ $comment->content }}</div>
        <button type="button" class="text-blue-600 text-xs reply-btn" data-id="{{ $comment->id }}">Trả lời</button>
        <!-- Hiển thị replies -->
        @if($comment->replies->count())
            <div class="ml-4">
                @include('partials.comments', ['comments' => $comment->replies])
            </div>
        @endif
    </div>
@endforeach