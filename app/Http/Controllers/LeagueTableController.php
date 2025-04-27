<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\Team;
use App\Models\Fixture;
use Illuminate\Http\Request;

class LeagueTableController extends Controller
{
    public function show(League $league)
    {
        $teams = Team::where('league_id', $league->id)->get();

        $standings = $teams->map(function ($team) use ($league) {
            $played = 0;
            $won = 0;
            $drawn = 0;
            $lost = 0;
            $points = 0;
            $goal_difference = 0;

            $fixtures = Fixture::where('status', 'completed')
                ->where('competition_id', function ($query) use ($league) {
                    $query->select('id')
                          ->from('competitions')
                          ->where('league_id', $league->id)
                          ->limit(1);
                })
                ->where(function($query) use ($team) {
                    $query->where('home_team_id', $team->id)
                          ->orWhere('away_team_id', $team->id);
                })
                ->get();

            foreach ($fixtures as $fixture) {
                if ($fixture->home_score === null || $fixture->away_score === null) {
                    continue; // skip incomplete scores
                }

                $played++;

                $isHome = $fixture->home_team_id == $team->id;
                $isAway = $fixture->away_team_id == $team->id;

                $teamScore = $isHome ? $fixture->home_score : $fixture->away_score;
                $opponentScore = $isHome ? $fixture->away_score : $fixture->home_score;

                $goal_difference += ($teamScore - $opponentScore);

                if ($teamScore > $opponentScore) {
                    $won++;
                    $points += 3;
                } elseif ($teamScore == $opponentScore) {
                    $drawn++;
                    $points += 1;
                } else {
                    $lost++;
                }
            }

            return (object) [
                'team_name' => $team->name,
                'played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'goal_difference' => $goal_difference,
                'points' => $points,
            ];
        });

        // Sort by Points and Goal Difference
        $sortedStandings = $standings->sortByDesc('points')->sortByDesc('goal_difference');

        return view('league-table', compact('league', 'sortedStandings'));
    }
}
