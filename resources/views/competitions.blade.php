@extends('layouts.app')

@section('title', 'Competitions')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-center">Competitions</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
    @foreach ($competitions as $competition)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl p-6 text-center">
            <h2 class="text-xl font-bold">{{ $competition->name }}</h2>
            <p class="text-gray-500">{{ $competition->league->name ?? '' }}</p>
            <p class="text-sm capitalize">{{ str_replace('_', ' ', $competition->type) }}</p>
            <a href="{{ url('/competitions/' . $competition->id) }}" class="text-blue-600 hover:underline mt-2 inline-block">
                View Competition
            </a>
        </div>
    @endforeach
</div>

@endsection
