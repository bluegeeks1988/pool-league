@extends('layouts.app')

@section('title', 'Teams')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-center">Teams</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach ($teams as $team)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-6 transition-all text-center">
            @if ($team->logo)
                <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="h-24 mx-auto mb-4 object-contain">
            @endif
            <h2 class="text-xl font-bold">{{ $team->name }}</h2>
            <a href="{{ url('/teams/' . $team->id) }}" class="text-blue-600 hover:underline mt-2 inline-block">
                View Team
            </a>
        </div>
    @endforeach
</div>

@endsection
