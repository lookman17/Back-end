<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profil;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $profil = Profil::all();
        return response()->json([
            'message' => 'Profil retrieved successfully',
            'data' => $profil
        ]);
    }

    // Menampilkan kategori berdasarkan ID
    public function show($id)
    {
        $profil = Profil::find($id);

        if (!$profil) {
            return response()->json([
                'message' => 'Profil not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Profil retrieved successfully',
            'data' => $profil
        ], 200);
    }

    // Store
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:profils',
        'deskripsi' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    }

    $profil = new Profil([
        'name' => $request->name,
        'deskripsi' => $request->deskripsi,
        'image' => $imagePath,
    ]);
    

    $profil->save();

    return response()->json([
        'message' => 'Profil created successfully',
        'data' => $profil
    ], 201);
}


    // Update
    public function update(Request $request, $id)
    {
        $profil = Profil::find($id);

        if (!$profil) {
            return response()->json(['message' => 'Profil not found'], 404);
        }

        $request->validate([
            'name' => 'nullable|string|max:255|unique:profils,name,' . $id,
            'deskripsi' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Perbarui name jika ada
        $profil->name = $request->name ?? $profil->name;

        // Perbarui deskripsi jika ada
        $profil->deskripsi = $request->deskripsi ?? $profil->deskripsi;

        // Perbarui image jika ada
        if ($request->hasFile('image')) {
            if ($profil->image) {
                Storage::disk('public')->delete($profil->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $profil->image = $path;
        }

        $profil->save();

        return response()->json([
            'message' => 'Profil updated successfully',
            'data' => $profil
        ]);
    }


    public function destroy($id)
    {
        $profil = Profil::find($id);

        if (!$profil) {
            return response()->json(['message' => 'Profil not found'], 404);
        }

        if ($profil->image) {
            Storage::disk('public')->delete($profil->image);
        }

        $profil->delete();

        return response()->json(['message' => 'Profil deleted successfully']);
    }
}
