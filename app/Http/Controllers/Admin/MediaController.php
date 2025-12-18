<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display media library
     */
    public function index(Request $request)
    {
        $query = Media::query()->latest();

        // Search - Only if not empty
        if ($search = $request->input('search')) {
            $query->where('file_name', 'like', "%{$search}%");
        }

        // Filter by type - Only if not empty
        if ($type = $request->input('type')) {
            $query->where('file_type', $type);
        }

        $media = $query->paginate(24);

        if ($request->expectsJson() || $request->ajax() || $request->has('ajax')) {
            return response()->json([
                'data' => $media->items(),
                'meta' => [
                    'current_page' => $media->currentPage(),
                    'last_page' => $media->lastPage(),
                    'total' => $media->total(),
                    'per_page' => $media->perPage(),
                ]
            ]);
        }

        return view('admin.media.index', compact('media'));
    }

    /**
     * Upload new media
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip|max:10240', // 10MB
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $safeName = Str::slug($fileName) . '-' . time() . '.' . $extension;

            // Determine file type
            $mimeType = $file->getMimeType();
            $fileType = 'document';
            if (str_starts_with($mimeType, 'image/')) {
                $fileType = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $fileType = 'video';
            }

            // Store file
            $path = $file->storeAs('uploads', $safeName, 'public');

            // Create Media model record
            $media = Media::create([
                'user_id' => auth()->id(),
                'file_name' => $originalName,
                'file_path' => $path,
                'file_type' => $fileType,
                'file_size' => $file->getSize(),
                'mime_type' => $mimeType,
            ]);

            $uploadedFiles[] = [
                'id' => $media->id,
                'name' => $media->file_name,
                'url' => asset('storage/' . $path),
                'thumb' => $fileType === 'image' ? asset('storage/' . $path) : null,
            ];
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
        ]);
    }

    /**
     * Delete media
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }

    /**
     * Get media details
     */
    public function show($id)
    {
        $media = Media::findOrFail($id);

        return response()->json([
            'id' => $media->id,
            'name' => $media->file_name,
            'file_name' => $media->file_name,
            'alt_text' => $media->alt_text,
            'caption' => $media->caption,
            'mime_type' => $media->mime_type,
            'size' => $media->getFormattedSize(),
            'url' => asset('storage/' . $media->file_path),
            'thumb' => $media->file_type === 'image' ? asset('storage/' . $media->file_path) : null,
            'created_at' => $media->created_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update media details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        $media = Media::findOrFail($id);
        $media->update([
            'file_name' => $request->input('file_name'),
            'alt_text' => $request->input('alt_text'),
            'caption' => $request->input('caption'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
        ]);
    }
}