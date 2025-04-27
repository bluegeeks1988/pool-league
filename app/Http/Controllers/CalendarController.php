<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Competition;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $leagueId = $request->query('league_id');
        $competitionId = $request->query('competition_id');

        $fixturesQuery = Fixture::whereMonth('match_date', $month)
            ->whereYear('match_date', $year);

        if ($leagueId) {
            $fixturesQuery->whereHas('homeTeam.league', function ($query) use ($leagueId) {
                $query->where('id', $leagueId);
            });
        }

        if ($competitionId) {
            $fixturesQuery->where('competition_id', $competitionId);
        }

        $fixtures = $fixturesQuery->orderBy('match_date')->get();

        $leagues = League::all();
        $competitions = Competition::all();

        $next7DaysFixtures = Fixture::where('match_date', '>=', Carbon::today())
            ->where('match_date', '<=', Carbon::today()->addDays(7))
            ->orderBy('match_date')
            ->get();

        return view('calendar', compact('fixtures', 'month', 'year', 'leagues', 'competitions', 'leagueId', 'competitionId', 'next7DaysFixtures'));
    }
}
