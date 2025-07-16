<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pengguna -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <!-- Total Psikolog -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Psikolog Aktif</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_psychologists'] }}</p>
            </div>
        </div>

         <!-- Verifikasi Psikolog -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Verifikasi Psikolog</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_psychologists'] }}</p>
            </div>
        </div>

        <!-- Verifikasi Pembayaran -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 flex items-center space-x-4">
             <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Verifikasi Bayar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_verifications'] }}</p>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Kolom Cerita Terbaru -->
        <div class="lg:col-span-3 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Cerita Terbaru</h3>
                <a href="{{ route('dashboard') }}" class="text-sm text-brand-pink hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Konten</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentStories as $story)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ Str::limit($story->content, 50) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $story->is_anonymous ? 'Anonim' : $story->user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $story->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-gray-500">Tidak ada cerita.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Pengguna Baru -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Pengguna Baru</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-brand-pink hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($newUsers as $user)
                <div class="flex items-center space-x-4">
                     <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }} - <span class="capitalize">{{$user->role}}</span></p>
                    </div>
                </div>
                @empty
                <p class="text-center py-4 text-gray-500">Tidak ada pengguna baru.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
