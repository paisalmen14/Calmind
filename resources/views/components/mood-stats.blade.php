@props(['moodStats', 'averageMood'])

<div class="bg-white dark:bg-gray-800 overflow-hidden rounded-2xl shadow-lg p-6 md:p-8">
    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Statistik Mood Mingguan</h3>

    <div class="mb-6">
        <h4 class="font-semibold text-gray-700 dark:text-gray-300">Riwayat Mood</h4>
        <div class="flex justify-around mt-2">
            @foreach($moodStats as $day => $emotion)
            <div class="text-center w-12">
                <span class="text-3xl">
                    @switch($emotion)
                    @case('Senang') 😊 @break
                    @case('Sedih') 😢 @break
                    @case('Marah') 😠 @break
                    @case('Netral') 😐 @break
                    @case('Takut') 😨 @break
                    @case('Jijik') 🤢 @break
                    @case('Terkejut') 😮 @break
                    @default <span class="text-gray-400">·</span>
                    @endswitch
                </span>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $day }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mb-6">
        <h4 class="font-semibold text-gray-700 dark:text-gray-300">Statistik Mood</h4>
        <div class="mt-2 h-40 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-2 flex justify-around items-end space-x-2">
            @php
            $moodLevels = ['Marah' => 1, 'Sedih' => 2, 'Takut' => 2, 'Jijik' => 2, 'Terkejut' => 3, 'Netral' => 3, 'Senang' => 4];
            @endphp
            @foreach($moodStats as $day => $emotion)
            <div class="flex-1 text-center flex flex-col justify-end items-center">
                <div class="bg-blue-400 rounded-t-md w-3/4" @style(['height'=> (($moodLevels[$emotion] ?? 0.2) * 25) . '%'])>
                    &nbsp;
                </div>
                {{-- INI ADALAH BAGIAN PENTING YANG MEMPERBAIKI MASALAH --}}
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $day }}</p>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between text-xs text-gray-400 px-1 mt-1">
            <span>Rendah</span>
            <span>Tinggi</span>
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-gray-700 dark:text-gray-300">Rata-rata Mood</h4>
        <div class="mt-2 bg-blue-50 dark:bg-gray-700/50 p-4 rounded-lg flex items-center space-x-4">
            <span class="text-4xl">
                @switch($averageMood)
                @case('Senang') 😊 @break
                @case('Sedih') 😢 @break
                @case('Marah') 😠 @break
                @case('Netral') 😐 @break
                @case('Takut') 😨 @break
                @case('Jijik') 🤢 @break
                @case('Terkejut') 😮 @break
                @default 😐
                @endswitch
            </span>
            <div>
                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $averageMood }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Jaga terus kesehatan mentalmu, ya!</p>
            </div>
        </div>
    </div>
</div>