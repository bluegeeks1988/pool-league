<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
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

        $fixtures = Fixture::whereBetween('match_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('match_date')
            ->get();

        $leagues = League::all();
        $competitions = Competition::all();

        return view('week-calendar', compact('fixtures', 'start', 'end', 'leagues', 'competitions'));
    }
}
