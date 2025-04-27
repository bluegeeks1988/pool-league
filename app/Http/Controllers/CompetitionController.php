<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Fixture;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::with('league')->latest()->get();
        return view('competitions', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $fixtures = Fixture::where('competition_id', $competition->id)
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        return view('competition-profile', compact('competition', 'fixtures'));
    }
}
