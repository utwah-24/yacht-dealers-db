@extends('layouts.dashboard')

@section('title', 'Summaries')

@section('content')
    <x-page-header title="Summaries" description="Order summaries linking bookings, guests, and catamaran photos.">
        @if ($summaries->isEmpty())
            <div class="yd-card">
                <p class="text-sm text-gray-500">No summaries yet.</p>
            </div>
        @else
            <x-data-table :count="$summaries->count()">
                <x-slot:head>
                    <th>ID</th>
                    <th>Booking ID</th>
                    <th>Guest</th>
                    <th>Catamaran photo</th>
                    <th>Photo path</th>
                </x-slot:head>
                <x-slot:body>
                    @foreach ($summaries as $summary)
                        <tr>
                            <td>{{ $summary->id }}</td>
                            <td>{{ $summary->booking_id }}</td>
                            <td>{{ $summary->guest?->name ?? ($summary->guest_id ? '#'.$summary->guest_id : '—') }}</td>
                            <td>{{ $summary->photo?->catamaran?->name ?? '—' }}</td>
                            <td class="max-w-xs truncate">{{ $summary->photo?->path ?? '—' }}</td>
                        </tr>
                    @endforeach
                </x-slot:body>
            </x-data-table>
        @endif
    </x-page-header>
@endsection
