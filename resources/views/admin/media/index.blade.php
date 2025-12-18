@extends('admin.layouts.app')

@section('title', 'Quản Lý Media')
@section('page-title', 'Media Library')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Media Library</h2>
            <button type="button" onclick="openUploadModal()"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center gap-2">
                <i class="fas fa-upload"></i> Upload Files
            </button>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border">
            <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" class="w-full border border-gray-300 rounded px-3 py-2"
                        placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">All Types</option>
                        <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
                        <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Filter
                    </button>
                    <a href="{{ route('admin.media.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Media Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($media as $item)
                <div
                    class="group relative bg-white border rounded-lg overflow-hidden hover:shadow-lg transition-all duration-300">
                    <div class="aspect-square bg-gray-100 relative overflow-hidden">
                        @if(str_starts_with($item->mime_type, 'image'))
                            <img src="{{ $item->getUrl('thumb') }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <i class="fas fa-file fa-3x"></i>
                            </div>
                        @endif

                        {{-- Hover Actions --}}
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2">
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full"
                                onclick="openEditModal({{ $item->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full"
                                onclick="deleteMedia({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-2">
                        <p class="text-sm font-medium truncate" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->human_readable_size }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-blue-700">
                        No media files found.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $media->links() }}
        </div>
    </div>

    {{-- Upload Modal (Tailwind Custom) --}}
    <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeUploadModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Upload Files
                            </h3>
                            <div class="mt-4">
                                <form id="uploadForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Files</label>
                                        <input type="file" name="files[]" id="fileInput" class="block w-full text-sm text-gray-500
                                                                        file:mr-4 file:py-2 file:px-4
                                                                        file:rounded-full file:border-0
                                                                        file:text-sm file:font-semibold
                                                                        file:bg-blue-50 file:text-blue-700
                                                                        hover:file:bg-blue-100" multiple
                                            accept="image/*,.pdf,.doc,.docx,.zip">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Max size: 10MB per file. Allowed: JPG, PNG, GIF, WebP, PDF, DOC, DOCX, ZIP
                                        </p>
                                    </div>

                                    <div id="uploadProgress" class="hidden mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>

                                    <div id="uploadResult" class="mt-2 text-sm"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="uploadFiles()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Upload
                    </button>
                    <button type="button" onclick="closeUploadModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeEditModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Edit Media Details
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div id="editPreview"
                                    class="bg-gray-100 rounded-lg flex items-center justify-center p-4 min-h-[200px]">
                                </div>
                                <div>
                                    <form id="editForm">
                                        <input type="hidden" id="editMediaId">
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">File Name</label>
                                            <input type="text" id="editFileName"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alt Text</label>
                                            <input type="text" id="editAltText"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                                            <textarea id="editCaption" rows="3"
                                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                        </div>
                                        <div class="text-xs text-gray-500 space-y-1">
                                            <p>Type: <span id="editMimeType" class="font-medium"></span></p>
                                            <p>Size: <span id="editSize" class="font-medium"></span></p>
                                            <p>Created: <span id="editCreated" class="font-medium"></span></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="updateMedia()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modal Functions
            function openUploadModal() {
                document.getElementById('uploadModal').classList.remove('hidden');
            }

            function closeUploadModal() {
                document.getElementById('uploadModal').classList.add('hidden');
                document.getElementById('uploadForm').reset();
                document.getElementById('uploadResult').innerHTML = '';
                document.getElementById('uploadProgress').classList.add('hidden');
            }

            function openEditModal(id) {
                fetch(`{{ url('/admin/media') }}/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editMediaId').value = data.id;
                        document.getElementById('editFileName').value = data.file_name;
                        document.getElementById('editAltText').value = data.alt_text || '';
                        document.getElementById('editCaption').value = data.caption || '';
                        document.getElementById('editMimeType').innerText = data.mime_type;
                        document.getElementById('editSize').innerText = data.size;
                        document.getElementById('editCreated').innerText = data.created_at;

                        const previewContainer = document.getElementById('editPreview');
                        if (data.mime_type.startsWith('image/')) {
                            previewContainer.innerHTML = `<img src="${data.url}" class="max-w-full max-h-64 rounded shadow-md">`;
                        } else {
                            previewContainer.innerHTML = `<i class="fas fa-file fa-5x text-gray-400"></i>`;
                        }

                        document.getElementById('editModal').classList.remove('hidden');
                    });
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            function updateMedia() {
                const id = document.getElementById('editMediaId').value;
                const formData = {
                    file_name: document.getElementById('editFileName').value,
                    alt_text: document.getElementById('editAltText').value,
                    caption: document.getElementById('editCaption').value,
                };

                fetch(`{{ url('/admin/media') }}/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify(formData),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Failed to update media: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating media.');
                    });
            }

            function uploadFiles() {
                const formData = new FormData(document.getElementById('uploadForm'));
                const fileInput = document.getElementById('fileInput');

                if (fileInput.files.length === 0) {
                    alert('Please select files to upload.');
                    return;
                }

                const progressBar = document.querySelector('#uploadProgress .bg-blue-600');
                const uploadProgress = document.getElementById('uploadProgress');
                const uploadResult = document.getElementById('uploadResult');

                uploadProgress.classList.remove('hidden');
                progressBar.style.width = '0%';
                uploadResult.innerHTML = '';

                // Simulate progress roughly or rely on browser events if using XHR (fetch doesn't report progress naturally without streams)
                // For better UX with standard fetch, we set it to something to show activity
                progressBar.style.width = '30%';

                fetch('{{ route("admin.media.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(async response => {
                        const contentType = response.headers.get("content-type");
                        if (contentType && contentType.includes("application/json")) {
                            return response.json();
                        } else {
                            const text = await response.text();
                            console.error('Server response:', text);
                            throw new Error('Server returned non-JSON response (check console)');
                        }
                    })
                    .then(data => {
                        progressBar.style.width = '100%';

                        if (data.success) {
                            uploadResult.innerHTML = '<span class="text-green-600 font-medium">Files uploaded successfully!</span>';
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            uploadResult.innerHTML = '<span class="text-red-600 font-medium">Upload failed!</span>';
                        }
                    })
                    .catch(error => {
                        progressBar.style.width = '0%';
                        uploadResult.innerHTML = '<span class="text-red-600 font-medium">Error: ' + error + '</span>';
                    });
            }

            function deleteMedia(id) {
                if (!confirm('Are you sure you want to delete this media?')) return;

                fetch(`{{ url('/admin/media') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Failed to delete media');
                        }
                    });
            }
        </script>
    @endpush
@endsection