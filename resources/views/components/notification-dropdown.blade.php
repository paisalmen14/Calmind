@props(['notifications'])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" class="relative inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 hover:text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
        </svg>
        @if($notifications->count() > 0)
            <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -right-1">
                {{ $notifications->count() }}
            </div>
        @endif
    </button>

    <!-- Dropdown menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 w-80 origin-top-right end-0 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
         style="display: none;">
        <div class="p-2 text-sm font-medium text-center text-gray-700 rounded-t-lg bg-gray-50">
            Notifikasi
        </div>
        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                <a href="{{ $notification->data['link'] ?? '#' }}?notif_id={{ $notification->id }}" class="flex px-4 py-3 hover:bg-gray-100">
                    <div class="w-full ps-3">
                        <div class="text-gray-600 text-sm mb-1.5">{!! $notification->data['message'] ?? 'Anda memiliki notifikasi baru.' !!}</div>
                        <div class="text-xs text-blue-600">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-3 text-center text-gray-500">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>
        <div class="p-2 text-sm font-medium text-center text-gray-700 rounded-b-lg bg-gray-50">
            {{-- Anda bisa menambahkan link ke halaman semua notifikasi di sini --}}
        </div>
    </div>
</div>
