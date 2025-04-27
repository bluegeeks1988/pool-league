<table class="min-w-full bg-white rounded-2xl overflow-hidden">
    <thead class="bg-gray-200 text-gray-700">
        <tr>
            <th class="py-2 px-4 text-left">Match</th>
            <th class="py-2 px-4 text-left">Date</th>
            <th class="py-2 px-4 text-left">Time</th>
            <th class="py-2 px-4 text-left">Status</th>
            <th class="py-2 px-4 text-left">Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fixtures as $fixture)
            <tr class="text-center border-t">
                <td class="py-2 px-4">{{ $fixture->homeTeam->name }} vs {{ $fixture->awayTeam->name }}</td>
                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($fixture->match_date)->format('d M Y') }}</td>
                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($fixture->match_time)->format('H:i') }}</td>
                <td class="py-2 px-4 capitalize">{{ $fixture->status }}</td>
                <td class="py-2 px-4">
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
