@extends('layouts.app')

@section('title', 'Fixtures Calendar')

@section('content')

@php
function getBadgeColorClass($eventType)
{
    switch (strtolower($eventType)) {
        case 'final':
            return 'bg-red-500 text-white';
        case 'semi-final':
            return 'bg-yellow-500 text-white';
        case 'quarter-final':
            return 'bg-green-500 text-white';
        case 'group stage':
            return 'bg-blue-500 text-white';
        default:
            return 'bg-purple-500 text-white';
    }
}
@endphp

<h1 class="text-3xl font-bold mb-8 text-center">Fixtures Calendar - {{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</h1>

<!-- Filters and Navigation -->
<div class="flex flex-wrap justify-between items-center mb-6">
    <form method="GET" action="{{ url('/calendar') }}" class="flex gap-4 mb-4 sm:mb-0">
        <select name="league_id" onchange="this.form.submit()" class="border rounded p-2">
            <option value="">All Leagues</option>
            @foreach ($leagues as $league)
                <option value="{{ $league->id }}" {{ $leagueId == $league->id ? 'selected' : '' }}>
                    {{ $league->name }}
                </option>
            @endforeach
        </select>

        <select name="competition_id" onchange="this.form.submit()" class="border rounded p-2">
            <option value="">All Competitions</option>
            @foreach ($competitions as $competition)
                <option value="{{ $competition->id }}" {{ $competitionId == $competition->id ? 'selected' : '' }}>
                    {{ $competition->name }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="flex gap-2">
        @php
            $prevMonth = \Carbon\Carbon::create($year, $month)->subMonth();
            $nextMonth = \Carbon\Carbon::create($year, $month)->addMonth();
        @endphp

        <a href="{{ url('/calendar?month=' . $prevMonth->month . '&year=' . $prevMonth->year) }}" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">
            ← {{ $prevMonth->format('F') }}
        </a>

        <a href="{{ url('/calendar?month=' . $nextMonth->month . '&year=' . $nextMonth->year) }}" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">
            {{ $nextMonth->format('F') }} →
        </a>

        <a href="{{ url('/calendar?month=' . now()->month . '&year=' . now()->year) }}" class="bg-blue-500 text-white hover:bg-blue-600 rounded px-4 py-2">
            Today
        </a>
    </div>
</div>

<!-- Calendar Grid -->
<div class="grid grid-cols-7 gap-4 text-center mb-10">
    <div class="font-bold">Sun</div>
    <div class="font-bold">Mon</div>
    <div class="font-bold">Tue</div>
    <div class="font-bold">Wed</div>
    <div class="font-bold">Thu</div>
    <div class="font-bold">Fri</div>
    <div class="font-bold">Sat</div>
</div>

@php
    $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
    $daysInMonth = $startOfMonth->daysInMonth;
    $startDay = $startOfMonth->dayOfWeek;
@endphp

<div class="grid grid-cols-7 gap-4 text-center">
    @for ($i = 0; $i < $startDay; $i++)
        <div></div>
    @endfor

    @for ($day = 1; $day <= $daysInMonth; $day++)
        @php
            $date = \Carbon\Carbon::create($year, $month, $day)->toDateString();
            $dayFixtures = $fixtures->filter(function ($fixture) use ($date) {
                return $fixture->match_date == $date;
            });
        @endphp
        <div class="bg-white p-2 rounded shadow relative min-h-[100px]">
            <div class="font-bold">{{ $day }}</div>

            @foreach ($dayFixtures as $fixture)
                @php
                    $isCompleted = $fixture->status === 'completed';
                @endphp
                <div class="mt-1">
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
                    class="text-xs underline truncate {{ $isCompleted ? 'text-green-600 hover:text-green-800' : 'text-blue-600 hover:text-blue-800' }}">
                        {{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}
                    </button>

                    @if (!empty($fixture->event_type))
                        <div class="text-[10px] px-2 py-0.5 rounded-full mt-1 inline-block {{ getBadgeColorClass($fixture->event_type) }}">
                            {{ strtoupper($fixture->event_type) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endfor
</div>

<!-- Sidebar Fixtures List -->
<h2 class="text-2xl font-bold my-8">Upcoming Fixtures This Month</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($fixtures as $fixture)
        <div class="bg-white p-4 rounded-2xl shadow hover:shadow-2xl">
            <div class="font-bold mb-1">{{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}</div>
            <div class="text-gray-500 text-sm">
                {{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}
                @ {{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}
            </div>
            <div class="text-sm capitalize text-gray-600">{{ $fixture->status }}</div>

            @if (!empty($fixture->event_type))
                <div class="text-[10px] px-2 py-0.5 rounded-full inline-block mt-2 {{ getBadgeColorClass($fixture->event_type) }}">
                    {{ strtoupper($fixture->event_type) }}
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Next 7 Days Widget -->
<h2 class="text-2xl font-bold my-8">Next 7 Days</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($next7DaysFixtures as $fixture)
        <div class="bg-white p-4 rounded-2xl shadow hover:shadow-2xl">
            <div class="font-bold mb-1">{{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}</div>
            <div class="text-gray-500 text-sm">
                {{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}
                @ {{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}
            </div>
            <div class="text-sm capitalize text-gray-600">{{ $fixture->status }}</div>
        </div>
    @endforeach
</div>

<!-- Modal Popup -->
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

<!-- JavaScript -->
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
