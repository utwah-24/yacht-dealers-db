@extends('layouts.dashboard')

@section('title', 'Catamarans')

@section('content')
    <x-page-header title="Catamarans" description="Yacht fleet catalog — name, services, description, photos, routes, and bookings.">
        @if ($catamarans->isEmpty())
            <div class="yd-card">
                <p class="text-sm text-gray-500">No catamarans yet. Add records via the API or phpMyAdmin.</p>
            </div>
        @else
            <x-data-table :count="$catamarans->count()">
                <x-slot:head>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Services provided</th>
                    <th>Description</th>
                    <th>Photos</th>
                    <th>Routes</th>
                    <th>Bookings</th>
                </x-slot:head>
                <x-slot:body>
                    @foreach ($catamarans as $catamaran)
                        <tr>
                            <td>{{ $catamaran->id }}</td>
                            <td class="font-medium text-gray-900">{{ $catamaran->name }}</td>
                            <td class="max-w-xs truncate">{{ $catamaran->services_provided }}</td>
                            <td class="max-w-xs truncate">{{ $catamaran->description }}</td>
                            <td>{{ $catamaran->photos_count }}</td>
                            <td>{{ $catamaran->routes_count }}</td>
                            <td>{{ $catamaran->bookings_count }}</td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        @endif
    </x-page-header>
@endsection
