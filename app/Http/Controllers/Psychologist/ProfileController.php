<?php
namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->psychologistProfile()->firstOrCreate(['user_id' => Auth::id()]);
        return view('psychologist.profile_picture.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate(['profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $profile = Auth::user()->psychologistProfile;

        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image_path) {
                Storage::disk('public')->delete($profile->profile_image_path);
            }
            $path = $request->file('profile_image')->store('profile_pictures', 'public');
            $profile->update(['profile_image_path' => $path]);
        }
        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}