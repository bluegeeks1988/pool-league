@extends('layouts.app')

@section('title', $competition->name . ' - Fixtures')

@section('content')

<h1 class="text-3xl font-bold mb-6">{{ $competition->name }} - Fixtures</h1>

<!-- Filter Links -->
<div class="mb-6 space-x-4">
    <a href="{{ url('/competition/' . $competition->id . '/fixtures?filter=upcoming') }}"
       class="{{ $filter === 'upcoming' ? 'text-blue-600 font-bold' : 'text-gray-700' }}">
        Upcoming
    </a>
    <a href="{{ url('/competition/' . $competition->id . '/fixtures?filter=completed') }}"
       class="{{ $filter === 'completed' ? 'text-blue-600 font-bold' : 'text-gray-700' }}">
        Completed
    </a>
</div>

<!-- Fixtures Table -->
<table class="min-w-full bg-white border">
    <thead class="bg-gray-200">
        <tr>
            <th class="py-2 px-4">Match</th>
            <th class="py-2 px-4">Date</th>
            <th class="py-2 px-4">Time</th>
            <th class="py-2 px-4">Status</th>
            <th class="py-2 px-4">Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fixtures as $fixture)
            <tr class="text-center">
                <td class="border px-4 py-2">{{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}</td>
                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}</td>
                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}</td>
                <td class="border px-4 py-2 capitalize">{{ $fixture->status }}</td>
                <td class="border px-4 py-2">
                    @if ($fixture->status === 'completed')
                        {{ $fixture->home_score }} - {{ $fixture->away_score }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
