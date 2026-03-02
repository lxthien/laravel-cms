@foreach($comments as $comment)
    <div class="mb-6 @if($comment->parent_id) ml-4 md:ml-8 border-l-2 border-slate-100 pl-4 @endif">
        <div class="flex gap-3">
            {{-- User Avatar Placeholder --}}
            <div
                class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold uppercase text-xs">
                {{ substr($comment->user ? $comment->user->name : $comment->name, 0, 1) }}
            </div>

            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-1">
                    <strong
                        class="text-slate-900 text-sm font-bold">{{ $comment->user ? $comment->user->name : $comment->name }}</strong>
                    <span class="flex items-center gap-1 text-slate-400 text-[10px] uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $comment->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>

                <div
                    class="text-slate-700 text-sm leading-relaxed mb-2 bg-white p-3 rounded-lg border border-slate-100 shadow-sm inline-block min-w-[120px]">
                    {{ $comment->content }}
                </div>

                <div>
                    <button type="button"
                        class="text-accent hover:text-orange-600 text-xs font-bold uppercase tracking-wide reply-btn flex items-center gap-1 transition-colors"
                        data-id="{{ $comment->id }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Trả lời
                    </button>
                </div>

                {{-- Recursive Replies --}}
                @if($comment->replies->count())
                    <div class="mt-4">
                        @include('frontend.partials.comments', ['comments' => $comment->replies])
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach