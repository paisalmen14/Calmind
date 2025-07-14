<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        @if($notifications->isNotEmpty())
            <span class="absolute top-0 right-0 block h-2 w-2 transform -translate-y-1/2 translate-x-1/2 rounded-full bg-red-500 ring-2 ring-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-20"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2">
        <div class="p-4 font-bold border-b">Notifikasi</div>
        <div class="divide-y max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ $notification->data['link'] }}?notif_id={{ $notification->id }}" class="block p-4 hover:bg-gray-100">
                    <p class="text-sm text-gray-700">{!! $notification->data['message'] !!}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="p-4 text-sm text-gray-500">Tidak ada notifikasi baru.</p>
            @endforelse
        </div>
    </div>
</div>