@extends('layouts.app')

@section('title', $team->name)

@section('content')

<div class="text-center mb-8">
    @if ($team->logo)
        <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="h-24 mx-auto mb-4 object-contain">
    @endif
    <h1 class="text-3xl font-bold">{{ $team->name }}</h1>
    <p class="text-gray-500">{{ $team->league->name ?? '' }}</p>
</div>

<h2 class="text-2xl font-bold mb-4">Players</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-10">
    @foreach ($players as $player)
        <div class="bg-white p-4 rounded-2xl shadow hover:shadow-2xl text-center">
            @if ($player->profile_image)
                <img src="{{ asset('storage/' . $player->profile_image) }}" alt="{{ $player->name }}" class="h-16 w-16 mx-auto rounded-full mb-2 object-cover">
            @endif
            <div class="font-bold">{{ $player->name }}</div>

            @if ($player->role === 'captain')
                <div class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full mt-1 inline-block">
                    Captain
                </div>
            @elseif ($player->role === 'vice-captain')
                <div class="text-xs bg-yellow-500 text-white px-2 py-0.5 rounded-full mt-1 inline-block">
                    Vice Captain
                </div>
            @endif
        </div>
    @endforeach
</div>

<h2 class="text-2xl font-bold mb-4">Fixtures</h2>
@include('partials.fixtures-table', ['fixtures' => $fixtures])

@endsection
