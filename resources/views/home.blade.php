@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <h1 class="text-4xl font-bold mb-10 text-center">🏆 Welcome to the League System</h1>

    @if ($nextFixture)
<div class="bg-white shadow p-6 mb-10 text-center rounded">
    <h2 class="text-2xl font-bold mb-2">Next Match Countdown</h2>
    <p class="text-lg mb-4">
        {{ $nextFixture->homeTeam->name }} vs {{ $nextFixture->awayTeam->name }}<br>
        <span class="text-gray-600">{{ \Carbon\Carbon::parse($nextFixture->match_date)->format('d M Y') }}
        at {{ \Carbon\Carbon::parse($nextFixture->match_time)->format('H:i') }}</span>
    </p>

    <div class="flex justify-center items-center gap-6 text-center text-blue-600 text-4xl font-bold" id="countdown">
    <div class="flex flex-col">
        <span id="days" class="countdown-number">00</span>
        <span class="text-sm font-normal text-gray-500">Days</span>
    </div>
    <div class="flex flex-col">
        <span id="hours" class="countdown-number">00</span>
        <span class="text-sm font-normal text-gray-500">Hours</span>
    </div>
    <div class="flex flex-col">
        <span id="minutes" class="countdown-number">00</span>
        <span class="text-sm font-normal text-gray-500">Minutes</span>
    </div>
    <div class="flex flex-col">
        <span id="seconds" class="countdown-number">00</span>
        <span class="text-sm font-normal text-gray-500">Seconds</span>
    </div>
</div>


<div id="match-started" class="text-2xl text-green-600 mt-6 hidden">
    Match Started!
</div>
</div>
@endif



    <!-- Upcoming Fixtures -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold mb-4">Upcoming Fixtures</h2>
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4">Match</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($upcomingFixtures as $fixture)
                    <tr class="text-center">
                        <td class="border px-4 py-2">{{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Leagues -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold mb-4">Leagues</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($leagues as $league)
                <div class="p-4 bg-white rounded shadow text-center">
                    <h3 class="text-xl font-bold">{{ $league->name }}</h3>
                    <p class="text-gray-600">{{ $league->season ?? '' }}</p>
                    <a href="{{ url('/league/' . $league->id) }}" class="text-blue-600 hover:underline mt-2 block">View League Table</a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Competitions -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold mb-4">Competitions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($competitions as $competition)
                <div class="p-4 bg-white rounded shadow text-center">
                    <h3 class="text-xl font-bold">{{ $competition->name }}</h3>
                    <p class="text-gray-600 capitalize">{{ str_replace('_', ' ', $competition->type) }}</p>
                    <a href="{{ url('/competitions/' . $competition->id) }}" class="text-blue-600 hover:underline mt-2 block">View Competition</a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Teams -->
    <div class="mb-10">
        <h2 class="text-2xl font-bold mb-4">Teams</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($teams as $team)
                <div class="p-4 bg-white rounded shadow text-center">
                    @if ($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo" class="h-16 mx-auto mb-4">
                    @endif
                    <h3 class="text-xl font-bold">{{ $team->name }}</h3>
                    <a href="{{ url('/teams/' . $team->id) }}" class="text-blue-600 hover:underline mt-2 block">View Team</a>
                </div>
            @endforeach
        </div>
    </div>

    @if ($nextFixture)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const matchDate = "{{ \Carbon\Carbon::parse($nextFixture->match_date)->format('Y-m-d') }}";
    const matchTime = "{{ \Carbon\Carbon::parse($nextFixture->match_time)->format('H:i:s') }}";
    const matchDateTime = new Date(matchDate + 'T' + matchTime);

    let timerInterval;

    function updateCountdown() {
        const now = new Date();
        const diff = matchDateTime.getTime() - now.getTime();

        if (diff <= 0) {
            const countdown = document.getElementById('countdown');
            const matchStarted = document.getElementById('match-started');

            if (countdown) {
                countdown.style.display = 'none'; // ✅ fully hide
            }
            if (matchStarted) {
                matchStarted.classList.remove('hidden'); // ✅ show match started
            }

            clearInterval(timerInterval);
            return;
        }

        if (document.getElementById('days')) {
            document.getElementById('days').innerText = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
        }
        if (document.getElementById('hours')) {
            document.getElementById('hours').innerText = String(Math.floor((diff / (1000 * 60 * 60)) % 24)).padStart(2, '0');
        }
        if (document.getElementById('minutes')) {
            document.getElementById('minutes').innerText = String(Math.floor((diff / (1000 * 60)) % 60)).padStart(2, '0');
        }
        if (document.getElementById('seconds')) {
            document.getElementById('seconds').innerText = String(Math.floor((diff / 1000) % 60)).padStart(2, '0');
        }
    }

    updateCountdown();
    timerInterval = setInterval(updateCountdown, 1000);
});
</script>
@endif






@endsection
