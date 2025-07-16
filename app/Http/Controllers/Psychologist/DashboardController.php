<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $psychologist = Auth::user();

        $stats = [
            'upcoming_consultations' => $psychologist->consultationsAsPsychologist()
                                                    ->where('status', 'confirmed')
                                                    ->where('requested_start_time', '>', now())
                                                    ->count(),
            'completed_consultations' => $psychologist->consultationsAsPsychologist()
                                                     ->where('status', 'completed')
                                                     ->count(),
        ];

        $upcomingConsultations = $psychologist->consultationsAsPsychologist()
                                            ->with('user')
                                            ->where('status', 'confirmed')
                                            ->where('requested_start_time', '>', now())
                                            ->orderBy('requested_start_time', 'asc')
                                            ->take(5)
                                            ->get();

        return view('psychologist.dashboard', compact('stats', 'upcomingConsultations'));
    }
}