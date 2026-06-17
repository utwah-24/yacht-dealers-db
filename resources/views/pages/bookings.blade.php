@extends('layouts.dashboard')

@section('title', 'Bookings')

@section('content')
    <x-page-header title="Bookings" description="Charter bookings linked to catamarans — location, charter type, duration, and price.">
        @if ($bookings->isEmpty())
            <div class="yd-card">
                <p class="text-sm text-gray-500">No bookings yet.</p>
            </div>
        @else
            <x-data-table :count="$bookings->count()">
                <x-slot:head>
                    <th>ID</th>
                    <th>Catamaran</th>
                    <th>Location</th>
                    <th>Charter type</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Guest</th>
                    <th>Extras</th>
                </x-slot:head>
                <x-slot:body>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td class="font-medium text-gray-900">{{ $booking->catamaran_name ?? $booking->catamaran?->name ?? '—' }}</td>
                            <td>{{ $booking->location_type?->label() }}</td>
                            <td>{{ $booking->charter_type?->label() }}</td>
                            <td>{{ $booking->duration }}h</td>
                            <td>${{ number_format($booking->charter_price, 2) }}</td>
                            <td>{{ $booking->guest?->name ?? '—' }}</td>
                            <td>{{ $booking->extras ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        @endif
    </x-page-header>
@endsection
