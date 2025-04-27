<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Competition;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FixtureController extends Controller
{
    public function index(Competition $competition, Request $request)
    {
        $filter = $request->query('filter', 'upcoming'); // default to upcoming

        $fixturesQuery = Fixture::where('competition_id', $competition->id);

        if ($filter === 'completed') {
            $fixturesQuery->where('status', 'completed');
        } else {
            $fixturesQuery->where('status', 'scheduled')
                ->whereDate('match_date', '>=', Carbon::today());
        }

        $fixtures = $fixturesQuery
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        return view('fixtures', compact('competition', 'fixtures', 'filter'));
    }
}
