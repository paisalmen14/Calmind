<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Consultation;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data statistik.
     */
    public function index()
    {
        // Data untuk kartu statistik
        $stats = [
            'total_users' => User::where('role', 'pengguna')->count(),
            'total_psychologists' => User::where('role', 'psikolog')->where('psychologist_status', 'approved')->count(),
            'pending_psychologists' => User::where('role', 'psikolog')->where('psychologist_status', 'pending')->count(),
            'pending_verifications' => Consultation::where('status', 'pending_verification')->count(),
        ];

        // Data untuk tabel
        $recentStories = Story::with('user')->latest()->take(5)->get();
        $newUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentStories', 'newUsers'));
    }
}