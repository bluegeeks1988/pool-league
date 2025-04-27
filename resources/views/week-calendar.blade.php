@extends('layouts.app')

@section('title', 'Week View Fixtures')

@section('content')

<h1 class="text-3xl font-bold mb-8 text-center">
    Fixtures - Week of {{ $start->format('d M') }} - {{ $end->format('d M Y') }}
</h1>

<!-- Filters + Navigation -->
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

    <!-- Week Navigation -->
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
                @php
                    $isCompleted = $fixture->status === 'completed';
                @endphp

                <div class="mt-2 {{ $isCompleted ? 'bg-gray-200 rounded p-1' : '' }}">
                    <button onclick="openFixtureModal(
                        '{{ $fixture->homeTeam->name }}',
                        '{{ $fixture->awayTeam->name }}',
                        '{{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}',
                        '{{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}',
                        '{{ $fixture->status }}',
                        '{{ $fixture->competition->name ?? '' }}',
                        '{{ $fixture->homeTeam->logo ? asset('storage/' . $fixture->homeTeam->logo) : '' }}',
                        '{{ $fixture->awayTeam->logo ? asset('storage/' . $fixture->awayTeam->logo) : '' }}'
                    )" 
                    class="text-xs underline truncate text-blue-600 hover:text-blue-800">
                        {{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}
                    </button>

                    @if (\Carbon\Carbon::parse($fixture->match_date)->isToday())
                        <div class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full mt-1 inline-block animate-pulse">
                            LIVE NOW
                        </div>
                    @endif
                </div>

            @empty
                <div class="text-xs text-gray-400 mt-2">No Matches</div>
            @endforelse
        </div>
    @endforeach

</div>

<!-- Modal Popup for Fixtures -->
<div id="fixtureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center relative">
        <button onclick="closeFixtureModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">&times;</button>

        <div class="flex justify-center items-center gap-4 mb-4">
            <img id="homeLogo" class="h-12 w-12 object-cover rounded-full" src="" alt="Home Team">
            <span class="text-xl font-bold" id="fixtureMatch"></span>
            <img id="awayLogo" class="h-12 w-12 object-cover rounded-full" src="" alt="Away Team">
        </div>

        <p id="fixtureDate" class="text-gray-600"></p>
        <p id="fixtureTime" class="text-gray-600"></p>
        <p id="fixtureStatus" class="text-sm text-gray-500 mt-2"></p>
        <p id="fixtureCompetition" class="text-sm text-blue-600 mt-2"></p>
    </div>
</div>

<script>
function openFixtureModal(homeTeam, awayTeam, date, time, status = '', competition = '', homeLogo = '', awayLogo = '') {
    document.getElementById('fixtureMatch').innerText = homeTeam + ' vs ' + awayTeam;
    document.getElementById('fixtureDate').innerText = "Date: " + date;
    document.getElementById('fixtureTime').innerText = "Time: " + time;
    document.getElementById('fixtureStatus').innerText = "Status: " + (status || 'Scheduled');
    document.getElementById('fixtureCompetition').innerText = competition ? ("Competition: " + competition) : '';

    if (homeLogo) {
        document.getElementById('homeLogo').src = homeLogo;
    }
    if (awayLogo) {
        document.getElementById('awayLogo').src = awayLogo;
    }

    document.getElementById('fixtureModal').classList.remove('hidden');
    document.getElementById('fixtureModal').classList.add('flex');
}

function closeFixtureModal() {
    document.getElementById('fixtureModal').classList.add('hidden');
}
</script>

@endsection
