{{-- resources/views/layouts/navigation.blade.php (Versi Final Disesuaikan) --}}
<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-lg border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                         <svg class="h-8 w-auto" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M24 4C12.95 4 4 12.95 4 24C4 35.05 12.95 44 24 44C35.05 44 44 35.05 44 24C44 12.95 35.05 4 24 4ZM24 42C14.06 42 6 33.94 6 24C6 14.06 14.06 6 24 6C33.94 6 42 14.06 42 24C42 33.94 33.94 42 24 42Z" fill="#F43F5E="/><path d="M24 18C21.24 18 19 20.24 19 23V25C19 27.76 21.24 30 24 30C26.76 30 29 27.76 29 25V23C29 20.24 26.76 18 24 18ZM24 28C22.34 28 21 26.66 21 25V23C21 21.34 22.34 20 24 20C25.66 20 27 21.34 27 23V25C27 26.66 25.66 28 24 28Z" fill="#E91E63"/>
                        </svg>
                        <span class="font-serif text-2xl font-bold text-gray-900 hidden sm:inline">Calmind</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Ruang Cerita') }}
                    </x-nav-link>
                    <x-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.*')">
                        {{ __('Artikel') }}
                    </x-nav-link>
                     <x-nav-link :href="route('chatbot.index')" :active="request()->routeIs('chatbot.index')">
                        {{ __('Konsultasi AI') }}
                    </x-nav-link>
                    
                    {{-- Logika untuk menampilkan link Ruang Chat / Konsultasi Profesional --}}
                    @if(Auth::user()->role === 'psikolog')
                        <x-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
                            {{ __('Ruang Chat') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'pengguna')
                        @if($hasActiveConsultation)
                            <x-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
                                {{-- Beri warna hijau jika ada konsultasi aktif --}}
                                <span class="text-green-600 font-bold">{{ __('Ruang Chat') }}</span>
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('consultations.index')" :active="request()->routeIs('consultations.*')">
                                {{-- Beri warna pink jika tidak ada konsultasi aktif --}}
                                <span class="text-brand-pink font-bold">{{ __('Konsultasi Profesional') }}</span>
                            </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Komponen Notifikasi Anda --}}
                <x-notification-dropdown />

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 bg-white hover:text-gray-800 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>

                    {{-- Konten Dropdown (Menu Admin, Psikolog, dll) --}}
                    <x-slot name="content">
                        @if(Auth::user()->role === 'admin')
                            <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Menu Admin') }}</div>
                            <x-dropdown-link :href="route('admin.users.index')">{{ __('Kelola Pengguna') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.articles.index')">{{ __('Manajemen Artikel') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.consultation.verifications.index')">{{ __('Verifikasi Konsultasi') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('admin.psychologists.index')">{{ __('Verifikasi Psikolog') }}</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                        @endif
                        
                        @if(Auth::user()->role === 'psikolog')
                             <div class="block px-4 py-2 text-xs text-gray-400">{{ __('Menu Psikolog') }}</div>
                             <x-dropdown-link :href="route('psychologist.availability.index')">{{ __('Kelola Jadwal') }}</x-dropdown-link>
                             <x-dropdown-link :href="route('psychologist.profile.edit')">{{ __('Pengaturan Profil') }}</x-dropdown-link>
                             <div class="border-t border-gray-200"></div>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profil Saya') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('consultations.history')">{{ __('Riwayat Konsultasi') }}</x-dropdown-link>
                        
                        <div class="border-t border-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <div class="-me-2 flex items-center sm:hidden">
                 <x-notification-dropdown />
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>
    
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Ruang Cerita') }}</x-responsive-nav-link>
            {{-- Tambahkan link responsive lainnya di sini jika perlu --}}
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4"><div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div><div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profil Saya') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}"><@csrf<x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link></form>
            </div>
        </div>
    </div>
</nav>