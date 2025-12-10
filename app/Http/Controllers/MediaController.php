<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display gallery index
     */
    public function index()
    {
        $medias = Media::latest()->paginate(20);
        return view('admin.media.index', compact('medias'));
    }

    /**
     * Upload new media
     */
    public function store(Request $request)
    {
        // Accept both 'file' and 'media' field names
        $fileField = $request->hasFile('media') ? 'media' : 'file';

        $validated = $request->validate([
            $fileField => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile($fileField)) {
            $file = $request->file($fileField);
            $path = $file->store('media', 'public');

            $media = Media::create([
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'alt_text' => $validated['alt_text'] ?? null,
                'description' => $validated['description'] ?? null,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'media' => $media,
                'url' => asset('storage/' . $media->path),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file provided',
        ], 400);
    }

    /**
     * Delete media
     */
    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Delete file from storage
        if (file_exists(storage_path('app/public/' . $media->path))) {
            unlink(storage_path('app/public/' . $media->path));
        }

        $media->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully',
            ]);
        }

        return redirect()->back()->with('success', 'Media deleted successfully');
    }

    /**
     * Update media metadata
     */
    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $media->update([
            'alt_text' => $validated['alt_text'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // Always return JSON for AJAX requests
        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'media' => $media,
        ]);
    }

    /**
     * Get all media as JSON (for AJAX)
     */
    public function show($id)
    {
        $media = Media::findOrFail($id);
        return response()->json($media);
    }

    /**
     * Get all media as JSON (for gallery picker)
     */
    public function getAll()
    {
        $medias = Media::latest()->get();
        return response()->json($medias);
    }
}
