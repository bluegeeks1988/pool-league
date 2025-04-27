<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueTableController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\WeekCalendarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);

// Fixtures and League Table
Route::get('/league/{league}', [LeagueTableController::class, 'show']);
Route::get('/competition/{competition}/fixtures', [FixtureController::class, 'index']);

// New Routes
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{competition}', [CompetitionController::class, 'show']);
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{team}', [TeamController::class, 'show']);
Route::get('/players', [PlayerController::class, 'index']);

Route::get('/calendar', [CalendarController::class, 'index']);