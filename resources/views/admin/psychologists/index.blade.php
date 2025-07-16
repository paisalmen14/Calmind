<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-3xl text-gray-900">
            {{ __('Verifikasi Psikolog') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
        <div class="p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Pendaftar Psikolog</h3>
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Universitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($psychologists as $psychologist)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $psychologist->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $psychologist->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $psychologist->psychologistProfile->university ?? 'N/A' }}
                                    ({{ $psychologist->psychologistProfile->graduation_year ?? 'N/A' }})
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500">
                                    <a href="{{ asset('storage/' . $psychologist->psychologistProfile->ktp_path) }}" target="_blank" class="hover:underline">Lihat KTP</a><br>
                                    <a href="{{ asset('storage/' . $psychologist->psychologistProfile->certificate_path) }}" target="_blank" class="hover:underline">Lihat Ijazah</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <form action="{{ route('admin.psychologists.approve', $psychologist) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-900">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.psychologists.reject', $psychologist) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">Tidak ada pendaftar psikolog yang perlu diverifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $psychologists->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
