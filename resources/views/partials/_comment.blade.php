<div class="border-b border-black pb-4 @if($comment->parent_id) ml-6 md:ml-12 border-l border-black pl-4 @endif">
    <div class="flex items-center mb-1">
        @if ($story->is_anonymous && $comment->user_id == $story->user_id)
            <p class="font-semibold text-black">Penulis Anonim</p>
        @else
            <p class="font-semibold text-black">{{ $comment->user->name }}</p>
        @endif
        <p class="text-xs font-gabarito text-black">- {{ $comment->created_at->diffForHumans() }}</p>
    </div>
    <p class="text-black">{{ $comment->content }}</p>

    <div class="mt-2 flex items-center space-x-4">
        @if(Auth::user()->role !== 'admin')
            <div x-data="{ open: false }" class="mt-2">
                <button @click="open = !open" class="text-sm text-[#E14434] font-gabarito hover:underline">Balas</button>
                <div x-show="open" x-cloak class="mt-2" style="display: none;">
                    <form action="{{ route('comments.store', $story) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="2" class="mt-1 block rounded-lg w-full text-sm bg-slate-50 shadow-sm focus:border-brand-purple focus:ring-brand-purple" placeholder="Tulis balasan..."></textarea>
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="flex justify-end mt-2">
                            <button type="button" @click="open = false" class="text-sm text-black font-gabarito mr-2">Batal</button>
                            <x-primary-button type="submit" class="py-1 px-3 text-sm text-black font-gabarito">Kirim</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @can('delete-comment', $comment)
            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:underline">Hapus</button>
            </form>
        @endcan
    </div>
</div>

@if ($comment->replies->isNotEmpty())
    <div class="space-y-4 mt-4">
        @foreach ($comment->replies as $reply)
            @include('partials._comment', ['comment' => $reply, 'story' => $story])
        @endforeach
    </div>
@endif