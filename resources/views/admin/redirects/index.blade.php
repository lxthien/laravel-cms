@extends('admin.layouts.app')

@section('title', 'Quản lý Redirect')
@section('page-title', 'Redirect Manager')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Danh sách Redirect (301/302...)</h2>
            <div class="flex gap-2">
                <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Import CSV
                </button>
                <a href="{{ route('admin.redirects.export') }}"
                    class="bg-slate-700 hover:bg-slate-800 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Export CSV
                </a>
                <a href="{{ route('admin.redirects.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Thêm Redirect
                </a>
            </div>
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

        {{-- Filter & Bulk Actions --}}
        <div class="bg-gray-50 rounded-xl p-5 mb-8 border border-gray-100">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <form method="GET" action="{{ route('admin.redirects.index') }}"
                    class="flex flex-wrap gap-4 items-end flex-1">
                    <div class="w-full md:w-64">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tìm
                            kiếm</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Source, destination, note..."
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    </div>

                    <div class="w-40">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kiểu
                            khớp</label>
                        <select name="type"
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            <option value="">Tất cả</option>
                            <option value="exact" {{ request('type') == 'exact' ? 'selected' : '' }}>Exact</option>
                            <option value="wildcard" {{ request('type') == 'wildcard' ? 'selected' : '' }}>Wildcard</option>
                            <option value="regex" {{ request('type') == 'regex' ? 'selected' : '' }}>Regex</option>
                        </select>
                    </div>

                    <div class="w-40">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Trạng
                            thái</label>
                        <select name="is_active"
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition-all shadow-sm">
                            Lọc
                        </button>
                        <a href="{{ route('admin.redirects.index') }}"
                            class="bg-white hover:bg-gray-50 text-gray-600 px-5 py-2 border border-gray-200 rounded-lg font-medium text-sm transition-all shadow-sm">
                            Reset
                        </a>
                    </div>
                </form>

                <div id="bulk-actions" class="hidden">
                    <button onclick="handleBulkDelete()"
                        class="bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 px-5 py-2 rounded-lg font-medium text-sm transition-all shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Xóa đã chọn (<span id="selected-count">0</span>)
                    </button>
                </div>
            </div>
        </div>

        {{-- Content Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr
                        class="bg-gray-50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left">Source URL</th>
                        <th class="px-6 py-4 text-left">Destination URL</th>
                        <th class="px-6 py-4 text-center">Type</th>
                        <th class="px-6 py-4 text-center">Hits</th>
                        <th class="px-6 py-4 text-center">Active</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($redirects as $r)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $r->id }}"
                                    class="redirect-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $r->source_url }}">
                                    {{ $r->source_url }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-mono mt-1">Code: {{ $r->status_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 truncate max-w-xs" title="{{ $r->destination_url }}">
                                    {{ $r->destination_url }}
                                </div>
                                @if($r->note)
                                    <div class="text-[10px] text-blue-400 italic mt-1">Note: {{ $r->note }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase tracking-tight
                                    @if($r->match_type === 'exact') bg-blue-100 text-blue-700
                                    @elseif($r->match_type === 'wildcard') bg-purple-100 text-purple-700
                                    @else bg-amber-100 text-amber-700
                                    @endif">
                                    {{ $r->match_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-semibold text-gray-700">{{ number_format($r->hit_count) }}</div>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ $r->last_hit_at ? $r->last_hit_at->diffForHumans() : 'Never' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="toggleRedirectStatus({{ $r->id }})" id="status-toggle-{{ $r->id }}" class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none 
                                    {{ $r->is_active ? 'bg-emerald-500' : 'bg-gray-200' }}">
                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out
                                    {{ $r->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.redirects.edit', $r) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">Sửa</a>
                                    <form method="POST" action="{{ route('admin.redirects.destroy', $r) }}"
                                        onsubmit="return confirm('Xóa redirect này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium font-medium">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <p class="text-lg font-medium">Không tìm thấy redirect nào.</p>
                                <p class="text-sm mt-1">Hãy thử thay đổi bộ lọc hoặc thêm redirect mới.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $redirects->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Import Modal --}}
    <div id="import-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Import Redirects từ CSV</h3>
                <form action="{{ route('admin.redirects.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file CSV</label>
                        <input type="file" name="csv_file" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                        <p class="mt-2 text-xs text-gray-500">Định dạng: Source, Destination, Type, Status, Active, Order,
                            Note</p>
                    </div>
                    <div class="mt-5 sm:mt-6 flex gap-3">
                        <button type="submit"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Bắt đầu Import
                        </button>
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleRedirectStatus(id) {
                fetch(`/admin/redirects/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const btn = document.getElementById(`status-toggle-${id}`);
                        const dot = btn.querySelector('span');
                        if (data.status) {
                            btn.classList.replace('bg-gray-200', 'bg-emerald-500');
                            dot.classList.replace('translate-x-0', 'translate-x-5');
                        } else {
                            btn.classList.replace('bg-emerald-500', 'bg-gray-200');
                            dot.classList.replace('translate-x-5', 'translate-x-0');
                        }
                    });
            }

            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.redirect-checkbox');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            selectAll.addEventListener('change', function () {
                checkboxes.forEach(c => c.checked = this.checked);
                updateBulkVisibility();
            });

            checkboxes.forEach(c => {
                c.addEventListener('change', updateBulkVisibility);
            });

            function updateBulkVisibility() {
                const checked = document.querySelectorAll('.redirect-checkbox:checked');
                if (checked.length > 0) {
                    bulkActions.classList.remove('hidden');
                    selectedCount.innerText = checked.length;
                } else {
                    bulkActions.classList.add('hidden');
                }
            }

            function handleBulkDelete() {
                if (!confirm('Bạn có chắc muốn xóa các redirect đã chọn?')) return;

                const ids = Array.from(document.querySelectorAll('.redirect-checkbox:checked')).map(c => c.value);

                fetch('{{ route("admin.redirects.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
            }
        </script>
    @endpush
@endsection