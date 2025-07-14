<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru ke dalam database.
     */
    public function store(Request $request, Story $story)
    {
        $request->validate([
            'content' => 'required|string|min:1',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $story->comments()->create([
            'content'   => $request->content,
            'user_id'   => Auth::id(), // <-- PERBAIKAN: Gunakan Auth::id()
            'parent_id' => $request->parent_id,
        ]);

        // PERBAIKAN: Gunakan Auth::user() untuk mendapatkan objek user
        if ($story->user_id !== Auth::id()) {
                $story->user->notify(new NewCommentNotification($story, Auth::user()));
            }

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Menghapus komentar dari database.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete-comment', $comment);

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
