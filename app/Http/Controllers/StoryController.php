<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    /**
     * Menampilkan semua cerita.
     * Halaman ini berfungsi sebagai 'dashboard' atau halaman utama setelah login.
     */
    public function index(Request $request)
    {
        // Query untuk mengambil cerita beserta relasi dan jumlah vote
        $query = Story::with('user', 'comments')
            ->withCount([
                'votes as upvotes_count' => fn ($q) => $q->where('vote', 1),
                'votes as downvotes_count' => fn ($q) => $q->where('vote', -1),
            ]);

        // Logika untuk pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('content', 'like', '%' . $searchTerm . '%');
        }

        // Logika untuk filter
        $filter = $request->input('filter', 'newest');

        switch ($filter) {
            case 'top':
                // Urutkan berdasarkan total skor (upvotes - downvotes)
                $query->orderByRaw('(SELECT SUM(vote) FROM votes WHERE stories.id = story_id) DESC');
                break;
            case 'popular':
                // Urutkan berdasarkan jumlah komentar
                $query->withCount('comments')->orderByDesc('comments_count');
                break;
            default:
                // Urutan default adalah yang terbaru
                $query->latest();
                break;
        }

        $stories = $query->paginate(10)->withQueryString();
        
        // PERBAIKAN: Mengarahkan ke view 'dashboard' yang sudah kita siapkan untuk menampilkan cerita.
        // Ini akan mengatasi error setelah login.
        return view('dashboard', compact('stories'));
    }

    /**
     * Menampilkan form untuk membuat cerita baru.
     */
    public function create()
    {
        // Pastikan view ini ada: resources/views/stories/create.blade.php
        return view('stories.create');
    }

    /**
     * Menyimpan cerita baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|min:10',
        ]);

        Story::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous'), 
        ]);

        // Mengarahkan kembali ke halaman dashboard (Ruang Cerita) dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Cerita berhasil dipublikasikan!');
    }

    /**
     * Menampilkan detail satu cerita.
     */
    public function show(Story $story)
    {
        $story->load(['comments' => function ($query) {
            $query->whereNull('parent_id')->with('user', 'replies.user'); 
        }]);

        // Pastikan view ini ada: resources/views/stories/show.blade.php
        return view('stories.show', compact('story'));
    }

    /**
     * Menampilkan form untuk mengedit cerita.
     */
    public function edit(Story $story)
    {
        Gate::authorize('update-story', $story);

        // Pastikan view ini ada: resources/views/stories/edit.blade.php
        return view('stories.edit', compact('story'));
    }

    /**
     * Memperbarui cerita yang ada di database.
     */
    public function update(Request $request, Story $story)
    {
        Gate::authorize('update-story', $story);

        $request->validate(['content' => 'required|min:10']);

        $story->update([
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        // PERBAIKAN: Mengarahkan kembali ke halaman dashboard untuk konsistensi.
        return redirect()->route('dashboard')->with('success', 'Cerita berhasil diperbarui!');
    }

    /**
     * Menghapus cerita dari database.
     */
    public function destroy(Story $story)
    {
        Gate::authorize('delete-story', $story);

        $story->delete();

        return redirect()->route('dashboard')->with('success', 'Cerita berhasil dihapus.');
    }
}