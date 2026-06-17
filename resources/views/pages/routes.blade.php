@extends('layouts.dashboard')

@section('title', 'Routes')

@section('content')
    <x-page-header title="Routes" description="Departure points and destinations for each catamaran.">
        @if ($routes->isEmpty())
            <div class="yd-card">
                <p class="text-sm text-gray-500">No routes yet.</p>
            </div>
        @else
            <x-data-table :count="$routes->count()">
                <x-slot:head>
                    <th>ID</th>
                    <th>Catamaran</th>
                    <th>Departure</th>
                    <th>Destinations</th>
                </x-slot:head>
                <x-slot:body>
                    @foreach ($routes as $route)
                        <tr>
                            <td>{{ $route->id }}</td>
                            <td class="font-medium text-gray-900">{{ $route->catamaran?->name ?? '—' }}</td>
                            <td>{{ $route->departure }}</td>
                            <td>{{ is_array($route->destinations) ? implode(', ', $route->destinations) : $route->destinations }}</td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        @endif
    </x-page-header>
@endsection
