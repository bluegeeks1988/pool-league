@extends('layouts.app')

@section('title', 'Week View Fixtures')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-center">
    Fixtures - Week of {{ $start->format('d M') }} - {{ $end->format('d M Y') }}
</h1>

<div class="flex flex-wrap justify-center items-center gap-4 mb-6">

    <!-- Team Filter -->
    <form method="GET" action="{{ url('/calendar/week') }}" class="flex gap-2">
        <input type="hidden" name="start" value="{{ $start->toDateString() }}">
        <select name="team_id" onchange="this.form.submit()" class="border rounded p-2">
            <option value="">All Teams</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}" {{ $teamId == $team->id ? 'selected' : '' }}>
                    {{ $team->name }}
                </option>
            @endforeach
        </select>
    </form>

    <!-- Navigation Buttons -->
    <div class="flex gap-2">
        <a href="{{ url('/calendar/week?start=' . $start->copy()->subWeek()->toDateString()) }}" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">
            ← Previous Week
        </a>

        <a href="{{ url('/calendar/week') }}" class="bg-blue-500 text-white hover:bg-blue-600 rounded px-4 py-2">
            This Week
        </a>

        <a href="{{ url('/calendar/week?start=' . $start->copy()->addWeek()->toDateString()) }}" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">
            Next Week →
        </a>
    </div>

</div>

<!-- Week Grid -->
<div class="grid grid-cols-1 md:grid-cols-7 gap-4 text-center">

    @foreach (\Carbon\CarbonPeriod::create($start, $end) as $date)
        <div class="bg-white rounded-2xl shadow p-4 min-h-[200px] flex flex-col">
            <div class="font-bold mb-2">
                {{ $date->format('D') }}<br>
                <span class="text-sm text-gray-500">{{ $date->format('d M') }}</span>
            </div>

            @php
                $dayFixtures = $fixtures->filter(function ($fixture) use ($date) {
                    return $fixture->match_date == $date->toDateString();
                });
            @endphp

            @forelse ($dayFixtures as $fixture)
                <div class="text-xs text-blue-600 hover:underline mt-2">
                    {{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}
                </div>
            @empty
                <div class="text-xs text-gray-400 mt-2">No Matches</div>
            @endforelse
        </div>
    @endforeach

</div>

@endsection
