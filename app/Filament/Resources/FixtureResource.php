<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FixtureResource\Pages;
use App\Filament\Resources\FixtureResource\RelationManagers;
use App\Models\Fixture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FixtureResource extends Resource
{
    protected static ?string $model = Fixture::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('competition_id')
                ->relationship('competition', 'name')
                ->required(),

            Select::make('home_team_id')
                ->relationship('homeTeam', 'name')
                ->label('Home Team')
                ->required(),

            Select::make('away_team_id')
                ->relationship('awayTeam', 'name')
                ->label('Away Team')
                ->required(),

            DatePicker::make('match_date')
                ->label('Match Date'),

            TimePicker::make('match_time')
                ->label('Match Time'),

            TextInput::make('home_score')
                ->numeric()
                ->nullable(),

            TextInput::make('away_score')
                ->numeric()
                ->nullable(),

            Select::make('status')
                ->options([
                    'scheduled' => 'Scheduled',
                    'completed' => 'Completed',
                ])
                ->default('scheduled')
                ->required(),
        ]);
}



public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('competition.name')
                ->label('Competition')
                ->sortable()
                ->searchable(),

            TextColumn::make('homeTeam.name')
                ->label('Home Team')
                ->sortable()
                ->searchable(),

            TextColumn::make('awayTeam.name')
                ->label('Away Team')
                ->sortable()
                ->searchable(),

            TextColumn::make('match_date')
                ->label('Date')
                ->date(),

            TextColumn::make('match_time')
                ->label('Time'),

            TextColumn::make('status')
                ->label('Status')
                ->sortable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFixtures::route('/'),
            'create' => Pages\CreateFixture::route('/create'),
            'edit' => Pages\EditFixture::route('/{record}/edit'),
        ];
    }
}
