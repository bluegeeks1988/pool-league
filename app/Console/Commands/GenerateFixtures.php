<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateFixtures extends Command
{
    protected $signature = 'fixtures:generate {competition_id} {start_date}';
    protected $description = 'Auto-generate fixtures for a competition starting from a specific date';

    public function handle()
    {
        $competitionId = $this->argument('competition_id');
        $startDate = Carbon::parse($this->argument('start_date'));

        $competition = Competition::findOrFail($competitionId);
        $teams = Team::where('league_id', $competition->league_id)->get();

        if ($teams->count() < 2) {
            $this->error('Not enough teams to generate fixtures.');
            return;
        }

        $fixtures = [];
        $matchDay = clone $startDate;

        // Generate round robin fixtures
        foreach ($teams as $i => $homeTeam) {
            for ($j = $i + 1; $j < count($teams); $j++) {
                $awayTeam = $teams[$j];

                $fixtures[] = [
                    'competition_id' => $competition->id,
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'match_date' => $matchDay->toDateString(),
                    'match_time' => '19:00:00', // Default time 7 PM
                    'status' => 'scheduled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Add 7 days for next match
                $matchDay->addDays(7);
            }
        }

        Fixture::insert($fixtures);

        $this->info('Fixtures generated successfully.');
    }
}
