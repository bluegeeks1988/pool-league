@extends('layouts.app')

@section('title', $competition->name)

@section('content')

<div class="text-center mb-8">
    <h1 class="text-3xl font-bold">{{ $competition->name }}</h1>
    <p class="text-gray-500">{{ $competition->league->name ?? '' }}</p>
    <p class="text-sm capitalize">{{ str_replace('_', ' ', $competition->type) }}</p>
</div>

<h2 class="text-2xl font-bold mb-4">Fixtures</h2>
@include('partials.fixtures-table', ['fixtures' => $fixtures])

@endsection
