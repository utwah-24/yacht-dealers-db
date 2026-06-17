@extends('layouts.dashboard')

@section('title', 'Guests')

@section('content')
    <x-page-header title="Guests" description="Guest contact details for each booking.">
        @if ($guests->isEmpty())
            <div class="yd-card">
                <p class="text-sm text-gray-500">No guests yet.</p>
            </div>
        @else
            <x-data-table :count="$guests->count()">
                <x-slot:head>
                    <th>ID</th>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Passengers</th>
                </x-slot:head>
                <x-slot:body>
                    @foreach ($guests as $guest)
                        <tr>
                            <td>{{ $guest->id }}</td>
                            <td>{{ $guest->booking_id }}</td>
                            <td class="font-medium text-gray-900">{{ $guest->name }}</td>
                            <td>{{ $guest->date?->format('Y-m-d') }}</td>
                            <td>{{ $guest->phone_number }}</td>
                            <td>{{ $guest->email }}</td>
                            <td>{{ $guest->number_of_passengers }}</td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        @endif
    </x-page-header>
@endsection
