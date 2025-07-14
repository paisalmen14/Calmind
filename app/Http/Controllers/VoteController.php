<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use App\Models\Vote;
use App\Notifications\NewUpvoteNotification;

class VoteController extends Controller
{
    public function vote(Request $request, Story $story)
    {
        $request->validate(['vote' => 'required|in:up,down']);

        $voteValue = $request->vote == 'up' ? 1 : -1;
        $user = auth()->user();

        $existingVote = $story->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            // Jika pengguna membatalkan vote (misal: klik suka lagi saat sudah suka)
            if ($existingVote->vote == $voteValue) {
                $existingVote->delete();
            } else {
                // Jika pengguna mengubah vote (misal: dari tidak suka menjadi suka)
                $existingVote->update(['vote' => $voteValue]);
            }
        } else {
            // Ini adalah vote baru dari pengguna ini untuk cerita ini.
            $story->votes()->create([
                'user_id' => $user->id,
                'vote' => $voteValue,
            ]);

            // [PERBAIKAN] Kirim notifikasi HANYA jika vote baru ini adalah "suka"
            if ($voteValue == 1 && $story->user_id !== $user->id) {
                $story->user->notify(new NewUpvoteNotification($story, $user));
            }
        }

        // Muat ulang jumlah vote yang baru
        $story->loadCount([
            'votes as upvotes_count' => fn($q) => $q->where('vote', 1),
            'votes as downvotes_count' => fn($q) => $q->where('vote', -1),
        ]);
        
        // Kembalikan jumlah terpisah
        return response()->json([
            'upvotes_count' => $story->upvotes_count,
            'downvotes_count' => $story->downvotes_count,
        ]);
    }
}