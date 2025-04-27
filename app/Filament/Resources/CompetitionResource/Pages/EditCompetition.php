<?php

namespace App\Filament\Resources\CompetitionResource\Pages;

use App\Filament\Resources\CompetitionResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Notifications\Notification;
use App\Models\Team;
use App\Models\Fixture;
use Carbon\Carbon;

class EditCompetition extends EditRecord
{
    protected static string $resource = CompetitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            Actions\Action::make('Generate Fixtures')
                ->action(function () {
                    $competition = $this->record;

                    $teams = Team::where('league_id', $competition->league_id)->get();

                    if ($teams->count() < 2) {
                        Notification::make()
                            ->title('Not enough teams')
                            ->danger()
                            ->send();
                        return;
                    }

                    $fixtures = [];
                    $startDate = Carbon::now();
                    $matchDay = clone $startDate;

                    foreach ($teams as $i => $homeTeam) {
                        for ($j = $i + 1; $j < $teams->count(); $j++) {
                            $awayTeam = $teams[$j];

                            $fixtures[] = [
                                'competition_id' => $competition->id,
                                'home_team_id' => $homeTeam->id,
                                'away_team_id' => $awayTeam->id,
                                'match_date' => $matchDay->toDateString(),
                                'match_time' => '19:00:00',
                                'status' => 'scheduled',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            $matchDay->addDays(7);
                        }
                    }

                    Fixture::insert($fixtures);

                    Notification::make()
                        ->title('Fixtures Generated Successfully')
                        ->success()
                        ->send();
                })
                ->color('success')
                ->icon('heroicon-o-bolt'),
        ];
    }
}
