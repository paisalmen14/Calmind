<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil psikolog.
     */
    public function edit()
    {
        $profile = Auth::user()->psychologistProfile()->firstOrCreate(['user_id' => Auth::id()]);
        return view('consultations.psychologist.profile.edit', compact('profile'));
    }

    /**
     * Memperbarui profil psikolog (harga, foto, dll.).
     */
    public function update(Request $request)
    {
        $request->validate([
            'price_per_hour' => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'specialization' => 'nullable|string|max:255',
        ]);

        $profile = Auth::user()->psychologistProfile;

        // Update foto profil jika ada file baru yang diunggah
        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada
            if ($profile->profile_image_path) {
                Storage::disk('public')->delete($profile->profile_image_path);
            }
            // Simpan foto baru
            $path = $request->file('profile_image')->store('profile_pictures', 'public');
            $profile->profile_image_path = $path;
        }

        // Update data lainnya
        $profile->price_per_hour = $request->price_per_hour;
        $profile->specialization = $request->specialization;
        $profile->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
