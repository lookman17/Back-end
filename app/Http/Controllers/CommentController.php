<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Menampilkan semua komentar
    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            'message' => 'Comments retrieved successfully',
            'data' => $comments
        ]);
    }

    // Menampilkan komentar berdasarkan ID
    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return response()->json([
            'message' => 'Comment retrieved successfully',
            'data' => $comment
        ]);
    }

    // Menambahkan komentar baru
    public function store(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:contents,id',  // Pastikan content_id ada di tabel contents
            'user_name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $comment = Comment::create([
            'content_id' => $request->content_id,
            'user_name' => $request->user_name,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => $comment
        ], 201);
    }

    // Mengupdate komentar berdasarkan ID
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $request->validate([
            'user_name' => 'nullable|string|max:255',
            'message' => 'nullable|string',
        ]);

        $comment->update([
            'user_name' => $request->user_name ?? $comment->user_name,
            'message' => $request->message ?? $comment->message,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    // Menghapus komentar berdasarkan ID
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
