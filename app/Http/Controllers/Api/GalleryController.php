<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Menampilkan semua data gallery
    public function index()
    {
        $galleries = Gallery::with('user:id,username')->get();
        return response()->json($galleries);
    }

    // Menampilkan detail gallery berdasarkan id
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        // Ubah image path menjadi URL lengkap
        $gallery->image = asset('storage/' . $gallery->image);

        return response()->json($gallery);
    }

    // Menambahkan data gallery baru
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('images', 'public');
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'image' => $image, 
            'description' => $request->description,
            'user_id' => $request->user_id,
        ]);

        return response()->json($gallery, 201);
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::delete('public/' . $gallery->image);
            }
            $image = $request->file('image')->store('images', 'public');
        } else {
            $image = $gallery->image;
        }

        $gallery->update([
            'title' => $request->title ?? $gallery->title,
            'image' => $image,
            'description' => $request->description ?? $gallery->description,
            'user_id' => $request->user_id ?? $gallery->user_id,
        ]);

        return response()->json($gallery);
    }

    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        if ($gallery->image) {
            Storage::delete('public/' . $gallery->image);
        }

        $gallery->delete();

        return response()->json(['message' => 'Gallery deleted successfully']);
    }
}
