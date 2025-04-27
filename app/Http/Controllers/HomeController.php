<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Competition;
use App\Models\Team;
use App\Models\Fixture;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $leagues = League::latest()->take(5)->get();
        $competitions = Competition::latest()->take(5)->get();
        $teams = Team::latest()->take(5)->get();
        $upcomingFixtures = Fixture::where('status', 'scheduled')
            ->whereDate('match_date', '>=', Carbon::today())
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->take(5)
            ->get();

        // Get the VERY NEXT fixture
        $nextFixture = Fixture::where('status', 'scheduled')
            ->whereDate('match_date', '>=', Carbon::today())
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->first();

        return view('home', compact('leagues', 'competitions', 'teams', 'upcomingFixtures', 'nextFixture'));
    }
}
