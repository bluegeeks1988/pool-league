@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $league->name }} - League Table</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-3xl font-bold mb-6">{{ $league->name }} - League Table</h1>

    <table class="min-w-full bg-white border">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4">Team</th>
                <th class="py-2 px-4">Played</th>
                <th class="py-2 px-4">Won</th>
                <th class="py-2 px-4">Drawn</th>
                <th class="py-2 px-4">Lost</th>
                <th class="py-2 px-4">Goal Difference</th>
                <th class="py-2 px-4">Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sortedStandings as $standing)
                <tr class="text-center">
                    <td class="border px-4 py-2">{{ $standing->team_name }}</td>
                    <td class="border px-4 py-2">{{ $standing->played }}</td>
                    <td class="border px-4 py-2">{{ $standing->won }}</td>
                    <td class="border px-4 py-2">{{ $standing->drawn }}</td>
                    <td class="border px-4 py-2">{{ $standing->lost }}</td>
                    <td class="border px-4 py-2">{{ $standing->goal_difference }}</td>
                    <td class="border px-4 py-2 font-bold">{{ $standing->points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

@endsection
