@extends('admin.layouts.app')

@section('title', 'Sửa Redirect')
@section('page-title', 'Chỉnh sửa Redirect')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.redirects.index') }}"
                class="text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách
            </a>
            <div class="text-[10px] text-gray-400 font-mono uppercase tracking-widest">UID: {{ $redirect->id }} | Created:
                {{ $redirect->created_at->format('d/m/Y') }}</div>
        </div>

        @if(session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            {{ session('warning') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.redirects.update', $redirect) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Source URL --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">Source URL (Từ)</label>
                            <input type="text" name="source_url" value="{{ old('source_url', $redirect->source_url) }}"
                                required placeholder="/old-slug hoặc https://domain.com/path"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            <p class="text-[10px] text-gray-400">Đường dẫn tương đối hoặc URL tuyệt đối.</p>
                            @error('source_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Destination URL --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">Destination URL
                                (Đến)</label>
                            <input type="text" name="destination_url"
                                value="{{ old('destination_url', $redirect->destination_url) }}" required
                                placeholder="/new-slug hoặc https://external-domain.com"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            <p class="text-[10px] text-gray-400">Nơi sẽ được chuyển hướng tới.</p>
                            @error('destination_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-4">
                        {{-- Match Type --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">Kiểu khớp (Match
                                Type)</label>
                            <select name="match_type"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                                <option value="exact" {{ old('match_type', $redirect->match_type) == 'exact' ? 'selected' : '' }}>Exact (Chính xác)</option>
                                <option value="wildcard" {{ old('match_type', $redirect->match_type) == 'wildcard' ? 'selected' : '' }}>Wildcard (Có dấu *)</option>
                                <option value="regex" {{ old('match_type', $redirect->match_type) == 'regex' ? 'selected' : '' }}>Regex (Biểu thức chính quy)</option>
                            </select>
                            @error('match_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status Code --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">HTTP Status Code</label>
                            <select name="status_code"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none">
                                <option value="301" {{ old('status_code', $redirect->status_code) == '301' ? 'selected' : '' }}>301 (Vĩnh viễn - Tốt cho SEO)</option>
                                <option value="302" {{ old('status_code', $redirect->status_code) == '302' ? 'selected' : '' }}>302 (Tạm thời)</option>
                                <option value="307" {{ old('status_code', $redirect->status_code) == '307' ? 'selected' : '' }}>307 (Tạm thời - Giữ phương thức POST)</option>
                                <option value="308" {{ old('status_code', $redirect->status_code) == '308' ? 'selected' : '' }}>308 (Vĩnh viễn - Giữ phương thức POST)</option>
                            </select>
                        </div>

                        {{-- Order --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">Thứ tự ưu tiên</label>
                            <input type="number" name="order" value="{{ old('order', $redirect->order) }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            <p class="text-[10px] text-gray-400">Số nhỏ hơn chạy trước.</p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-4">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-tight">Ghi chú (Note)</label>
                        <textarea name="note" rows="2" placeholder="Lý do chuyển hướng..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none">{{ old('note', $redirect->note) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $redirect->is_active) == '1' ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Đang kích hoạt</label>
                    </div>

                    <div class="pt-8 border-t border-gray-50 flex justify-between items-center">
                        <div class="text-xs text-gray-400">Hits: <span
                                class="font-bold text-blue-500">{{ number_format($redirect->hit_count) }}</span> | Last hit:
                            {{ $redirect->last_hit_at ? $redirect->last_hit_at->diffForHumans() : 'Never' }}</div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.redirects.index') }}"
                                class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">
                                Hủy
                            </a>
                            <button type="submit"
                                class="px-10 py-3 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                Cập nhật Redirect
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection