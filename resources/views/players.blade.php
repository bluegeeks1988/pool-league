@extends('layouts.app')

@section('title', 'Players')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-center">Players</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach ($players as $player)
        <div class="bg-white p-4 rounded-2xl shadow hover:shadow-2xl text-center">
            @if ($player->profile_image)
                <img src="{{ asset('storage/' . $player->profile_image) }}" alt="{{ $player->name }}" class="h-20 w-20 rounded-full mx-auto mb-2 object-cover">
            @endif
            <h2 class="font-bold">{{ $player->name }}</h2>
            <p class="text-sm text-gray-500">{{ $player->team->name ?? '' }}</p>
        </div>
    @endforeach
</div>

@endsection
