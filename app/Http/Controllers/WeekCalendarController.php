<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use App\Models\League;
use App\Models\Competition;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeekCalendarController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->query('start', Carbon::now()->startOfWeek(Carbon::MONDAY));
        $start = Carbon::parse($weekStart);
        $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $teamId = $request->query('team_id');

        $fixturesQuery = Fixture::whereBetween('match_date', [$start->toDateString(), $end->toDateString()]);

        if ($teamId) {
            $fixturesQuery->where(function($query) use ($teamId) {
                $query->where('home_team_id', $teamId)
                      ->orWhere('away_team_id', $teamId);
            });
        }

        $fixtures = $fixturesQuery->orderBy('match_date')->get();

        $teams = Team::orderBy('name')->get();
        $leagues = League::all();
        $competitions = Competition::all();

        return view('week-calendar', compact('fixtures', 'start', 'end', 'teams', 'teamId', 'leagues', 'competitions'));
    }
}
