<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Comment; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;
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
            'user_id'   => auth()->id(),
            'parent_id' => $request->parent_id,
        ]);

        if ($story->user_id !== auth()->id()) {
                $story->user->notify(new NewCommentNotification($story, auth()->user()));
            }

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Menghapus komentar dari database.
     */
    public function destroy(Comment $comment)
    {
        // Otorisasi menggunakan Gate yang baru kita buat
        Gate::authorize('delete-comment', $comment);

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}