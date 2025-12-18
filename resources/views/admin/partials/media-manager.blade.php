{{-- Media Manager Modal --}}
<div id="mediaManagerModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
            onclick="MediaManager.close()"></div>

        <!-- Modal panel -->
        <div
            class="relative inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-5xl sm:w-full h-[80vh] flex flex-col">
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Media Manager</h3>
                <button type="button" onclick="MediaManager.close()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Toolbar -->
            <div class="bg-white px-6 py-4 border-b flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" id="mm-search" placeholder="Search media..."
                        class="w-full border rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                        onkeyup="MediaManager.handleSearch(event)">
                </div>
                <select id="mm-type" class="border rounded px-3 py-2 text-sm" onchange="MediaManager.loadMedia(1)">
                    <option value="">All Types</option>
                    <option value="image">Images</option>
                    <option value="video">Videos</option>
                    <option value="document">Documents</option>
                </select>
                <div class="ml-auto">
                    {{-- Upload input (hidden) --}}
                    <input type="file" id="mm-upload-input" class="hidden" multiple
                        onchange="MediaManager.handleUpload(this)">
                    <button type="button" onclick="document.getElementById('mm-upload-input').click()"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700">
                        Upload New
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div id="mm-grid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
                    {{-- Populated by JS --}}
                </div>

                {{-- Empty State --}}
                <div id="mm-empty" class="hidden py-20 text-center">
                    <div class="text-gray-400 mb-2">
                        <i class="fas fa-images fa-3x"></i>
                    </div>
                    <p class="text-gray-500">No media files found.</p>
                </div>

                {{-- Loading Spinner --}}
                <div id="mm-loader" class="py-10 text-center items-center justify-center flex flex-col">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                    <p class="text-sm text-gray-500">Loading library...</p>
                </div>

                {{-- Pagination / Load More --}}
                <div id="mm-more" class="mt-8 text-center hidden">
                    <button type="button" onclick="MediaManager.loadMore()"
                        class="text-blue-600 font-medium hover:underline">
                        Load More
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-white px-6 py-4 border-t flex justify-between items-center">
                <div id="mm-selection-info" class="text-sm text-gray-500">
                    No item selected
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="MediaManager.close()"
                        class="bg-white border rounded px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" id="mm-select-btn" disabled onclick="MediaManager.confirmSelection()"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                        Select
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.MediaManager = {
        currentCallback: null,
        multiple: false,
        selectedItems: [],
        currentPage: 1,
        lastPage: 1,
        searchQuery: '',
        isLoading: false,

        open(callback, multiple = false) {
            this.currentCallback = callback;
            this.multiple = multiple;
            this.selectedItems = [];
            this.currentPage = 1;
            this.updateSelectionInfo();

            document.getElementById('mediaManagerModal').classList.remove('hidden');
            this.loadMedia(1);
        },

        close() {
            document.getElementById('mediaManagerModal').classList.add('hidden');
        },

        loadMedia(page = 1) {
            if (this.isLoading) return;
            this.isLoading = true;
            this.currentPage = page;

            if (page === 1) {
                document.getElementById('mm-grid').innerHTML = '';
                document.getElementById('mm-loader').classList.remove('hidden');
                document.getElementById('mm-empty').classList.add('hidden');
            }

            const type = document.getElementById('mm-type').value;
            const search = document.getElementById('mm-search').value;
            const url = `{{ route('admin.media.index') }}?page=${page}&type=${type}&search=${search}&ajax=1&t=${new Date().getTime()}`;
            console.log('Fetching media from:', url);

            fetch(url, {
                headers: { 'Accept': 'application/json' }
            })
                .then(res => {
                    console.log('Response status:', res.status);
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    this.isLoading = false;
                    document.getElementById('mm-loader').classList.add('hidden');

                    if (!data || !data.meta) {
                        console.error('Missing metadata in response');
                        return;
                    }

                    this.lastPage = data.meta.last_page;

                    if (!data.data || data.data.length === 0) {
                        if (page === 1) {
                            document.getElementById('mm-empty').classList.remove('hidden');
                        }
                    } else {
                        this.renderItems(data.data);
                    }

                    if (this.currentPage < this.lastPage) {
                        document.getElementById('mm-more').classList.remove('hidden');
                    } else {
                        document.getElementById('mm-more').classList.add('hidden');
                    }
                })
                .catch(err => {
                    console.error('Media Manager load error:', err);
                    this.isLoading = false;
                    document.getElementById('mm-loader').classList.add('hidden');
                    alert('Error loading media library. check console for details.');
                });
        },

        loadMore() {
            if (this.currentPage < this.lastPage) {
                this.loadMedia(this.currentPage + 1);
            }
        },

        handleSearch(e) {
            if (this.searchTimer) clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.loadMedia(1), 500);
        },

        renderItems(items) {
            const grid = document.getElementById('mm-grid');
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = `group relative bg-white border rounded cursor-pointer hover:border-blue-500 overflow-hidden transition-all ${this.isSelected(item.id) ? 'border-2 border-blue-500' : ''}`;
                div.dataset.id = item.id;
                div.onclick = () => this.toggleSelection(item);

                let preview = '';
                if (item.file_type === 'image') {
                    preview = `<img src="${item.url}" class="w-full h-full object-cover">`;
                } else {
                    preview = `<div class="flex items-center justify-center h-full text-gray-400 bg-gray-100"><i class="fas fa-file fa-2x"></i></div>`;
                }

                div.innerHTML = `
                <div class="aspect-square relative flex items-center justify-center">
                    ${preview}
                    <div class="absolute inset-x-0 bottom-0 bg-black bg-opacity-50 text-white text-[10px] p-1 truncate opacity-0 group-hover:opacity-100 transition-opacity">
                        ${item.file_name}
                    </div>
                </div>
            `;
                grid.appendChild(div);
            });
        },

        toggleSelection(item) {
            const index = this.selectedItems.findIndex(i => i.id === item.id);

            if (index > -1) {
                this.selectedItems.splice(index, 1);
            } else {
                if (!this.multiple) {
                    this.selectedItems = [item];
                } else {
                    this.selectedItems.push(item);
                }
            }

            this.updateSelectionUI();
            this.updateSelectionInfo();
        },

        isSelected(id) {
            return this.selectedItems.some(i => i.id === id);
        },

        updateSelectionUI() {
            const gridItems = document.querySelectorAll('#mm-grid > div');
            gridItems.forEach(el => {
                const id = parseInt(el.dataset.id);
                if (this.isSelected(id)) {
                    el.classList.add('border-2', 'border-blue-500');
                } else {
                    el.classList.remove('border-2', 'border-blue-500');
                }
            });
        },

        updateSelectionInfo() {
            const info = document.getElementById('mm-selection-info');
            const btn = document.getElementById('mm-select-btn');

            if (this.selectedItems.length > 0) {
                info.innerText = `${this.selectedItems.length} item(s) selected`;
                btn.disabled = false;
            } else {
                info.innerText = 'No item selected';
                btn.disabled = true;
            }
        },

        confirmSelection() {
            if (this.currentCallback) {
                this.currentCallback(this.multiple ? this.selectedItems : this.selectedItems[0]);
            }
            this.close();
        },

        handleUpload(input) {
            if (input.files.length === 0) return;

            const formData = new FormData();
            Array.from(input.files).forEach(file => formData.append('files[]', file));

            document.getElementById('mm-loader').classList.remove('hidden');

            fetch('{{ route('admin.media.upload') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.loadMedia(1);
                    } else {
                        alert('Upload failed');
                    }
                });
        }
    };
</script>