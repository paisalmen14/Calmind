<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoodSurveyController extends Controller
{
    /**
     * Display the mood survey page.
     */
    public function index()
    {
        return view('survey.index');
    }
}
