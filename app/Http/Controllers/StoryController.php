<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;
use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    // Menampilkan semua cerita
    public function index(Request $request)
    {
            // Ganti query lama dengan ini untuk memuat jumlah upvote dan downvote
        $query = Story::with('user')
            ->withCount([
                'votes as upvotes_count' => fn ($q) => $q->where('vote', 1),
                'votes as downvotes_count' => fn ($q) => $q->where('vote', -1),
            ]);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('content', 'like', '%' . $searchTerm . '%');
        }

        $filter = $request->input('filter', 'newest');

        switch ($filter) {
            case 'top':
                // Urutkan berdasarkan selisih upvote dan downvote
                $query->orderByRaw('(SELECT SUM(vote) FROM votes WHERE story_id = stories.id) DESC');
                break;
            case 'popular':
                $query->withCount('comments')->orderByDesc('comments_count');
                break;
            default:
                $query->latest();
                break;
        }

        $stories = $query->paginate(10)->withQueryString();
        
        return view('dashboard', compact('stories'));
        
    }

    // Menampilkan form untuk membuat cerita
    public function create()
    {
        return view('stories.create');
    }

    // Menyimpan cerita baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|min:10',
        ]);

        Story::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous'), 
        ]);

        return redirect()->route('stories.index')->with('success', 'Cerita berhasil dipublikasikan!');
    }

   
    public function show(Story $story)
{
    
    $story->load(['comments' => function ($query) {
        $query->whereNull('parent_id')->with('user', 'replies.user'); 
    }]);

    return view('stories.show', compact('story'));
}

    public function edit(Story $story)
    {
        // Otorisasi menggunakan Gate yang sudah kita buat
        Gate::authorize('update-story', $story);

        return view('stories.edit', compact('story'));
    }

    // Method untuk menyimpan perubahan
    public function update(Request $request, Story $story)
    {
        Gate::authorize('update-story', $story);

        $request->validate(['content' => 'required|min:10']);

        $story->update([
            'content' => $request->content,
            'is_anonymous' => $request->has('is_anonymous'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Cerita berhasil diperbarui!');
    }

    // Method untuk menghapus story
    public function destroy(Story $story)
    {
        Gate::authorize('delete-story', $story);

        $story->delete();

        return redirect()->route('dashboard')->with('success', 'Cerita berhasil dihapus.');
    }
    }