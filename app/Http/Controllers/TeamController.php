<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Fixture;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('league')->latest()->get();
        return view('teams', compact('teams'));
    }

    public function show(Team $team)
    {
        $players = $team->players; // players from relation
        $fixtures = Fixture::where(function ($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();

        return view('team-profile', compact('team', 'players', 'fixtures'));
    }
}
