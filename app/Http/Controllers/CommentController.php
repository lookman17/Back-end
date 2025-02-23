<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Menampilkan semua komentar
    public function index()
    {
        $comments = Comment::with(['user:id,name,username,role,profile_photo'])->get();

        return response()->json([
            'message' => 'Comments retrieved successfully',
            'data' => $comments
        ]);
    }

    public function show($id)
{
    $comments = Comment::with('user')->where('gallery_id', $id)->get();

    return response()->json([
        'message' => 'Comments retrieved successfully',
        'data' => $comments
    ]);
}



    // Menambahkan komentar baru
    public function store(Request $request)
    {
        $request->validate([
            'gallery_id' => 'required|exists:galleries,id',  // Pastikan gallery_id ada di tabel galleries
            'user_id' => 'required|exists:users,id',  // Pastikan user_id ada di tabel users
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'gallery_id' => $request->gallery_id,
            'user_id' => $request->user_id,
            'content' => $request->content,
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
            'user_id' => 'nullable|exists:users,id',  // Pastikan user_id ada di tabel users
            'content' => 'nullable|string',
        ]);

        $comment->update([
            'user_id' => $request->user_id ?? $comment->user_id,
            'content' => $request->content ?? $comment->content,
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
