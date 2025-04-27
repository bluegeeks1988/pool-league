<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Filament\Resources\PlayerResource\RelationManagers;
use App\Models\Player;
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

class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('team_id')
                ->relationship('team', 'name')
                ->required(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            FileUpload::make('profile_image')
                ->directory('player-profiles')
                ->image()
                ->nullable(),

            Select::make('role')
                ->label('Role')
                ->options([
                    'player' => 'Player',
                    'captain' => 'Captain',
                    'vice-captain' => 'Vice Captain',
                ])
                ->default('player')
                ->required(),
        ]);
}



public static function table(Table $table): Table
{
    return $table
        ->columns([
            ImageColumn::make('profile_image')
                ->label('Photo')
                ->circular()
                ->size(40),

            TextColumn::make('name')
                ->label('Player Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('team.name')
                ->label('Team')
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }
}
