<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'League Management')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- TailwindCSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .countdown-number {
            font-size: 3rem;
            transition: transform 0.3s ease;
        }
        .countdown-number:hover {
            transform: scale(1.2);
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
            <div class="text-2xl font-bold text-blue-600">
                <a href="/">League System</a>
            </div>
            <div class="space-x-6 text-lg font-semibold">
                <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
                <a href="/competitions" class="text-gray-700 hover:text-blue-600">Competitions</a>
                <a href="/teams" class="text-gray-700 hover:text-blue-600">Teams</a>
                <a href="/players" class="text-gray-700 hover:text-blue-600">Players</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </div>

</body>
</html>
