@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="px-5 py-6 lg:px-8 lg:py-7">
        {{-- Header --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-[22px] font-semibold text-gray-900">Dashboard</h1>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Time period</span>
                <select class="h-9 rounded-lg border border-yd-line bg-white px-3 text-sm text-gray-700 shadow-sm outline-none focus:border-yd-green focus:ring-2 focus:ring-yd-green-soft">
                    <option>Last 12 days</option>
                    <option>Last 30 days</option>
                    <option>This year</option>
                </select>
            </div>
        </div>

        {{-- KPI row --}}
        <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ([
                ['label' => 'Total guests', 'key' => 'guests'],
                ['label' => 'Total revenue', 'key' => 'revenue'],
                ['label' => 'Total bookings', 'key' => 'bookings'],
                ['label' => 'Total catamarans', 'key' => 'catamarans'],
            ] as $card)
                @php $stat = $stats[$card['key']]; @endphp
                <div class="yd-card min-h-[128px]">
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                    <p class="yd-kpi-value">{{ $stat['value'] }}</p>
                    <div class="mt-4 flex items-end justify-between">
                        <span class="text-xs text-gray-400">{{ $stat['sub'] }}</span>
                        @if ($card['key'] !== 'catamarans')
                            <span class="{{ $stat['up'] ? 'yd-trend-up' : 'yd-trend-down' }}">
                                @if ($stat['up'])
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04L10.75 5.612V16.25A.75.75 0 0110 17z" clip-rule="evenodd"/></svg>
                                @else
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/></svg>
                                @endif
                                {{ abs($stat['trend']) }}%
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach

            <button type="button" class="yd-card flex min-h-[128px] flex-col items-center justify-center border-dashed bg-transparent text-gray-400 transition hover:border-yd-green hover:text-yd-green">
                <span class="mb-2 flex h-9 w-9 items-center justify-center rounded-full border border-yd-line text-lg leading-none">+</span>
                <span class="text-sm font-medium">Add data</span>
            </button>
        </div>

        {{-- Bar chart --}}
        <div class="yd-card mb-5">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-base font-semibold text-gray-900">Charter sales</h2>
                <div class="flex items-center gap-5 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-sm bg-[#4C78FF]"></span> Bookings</span>
                    <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-sm bg-[#FF9F43]"></span> Revenue</span>
                </div>
            </div>
            <div class="relative h-[280px] w-full">
                <canvas id="charterChart" class="!h-[280px] !max-h-[280px]"></canvas>
            </div>
        </div>

        {{-- Bottom widgets --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            {{-- Donut + legend --}}
            <div class="yd-card">
                <h2 class="mb-5 text-base font-semibold text-gray-900">Bookings by charter type</h2>
                <div class="grid grid-cols-1 items-center gap-6 md:grid-cols-[1fr_auto]">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        @php
                            $colors = ['#4C78FF', '#FF9F43', '#9B6BFF', '#2EC4B6', '#FF6B9D', '#7CB518', '#F15BB5', '#00BBF9', '#FEE440', '#8338EC'];
                        @endphp
                        @foreach ($charterChart as $index => $item)
                            <div class="flex items-center justify-between gap-3">
                                <span class="flex min-w-0 items-center gap-2 text-gray-600">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $colors[$index % count($colors)] }}"></span>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </span>
                                <span class="font-medium text-gray-900">{{ $item['percent'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mx-auto h-[180px] w-[180px]">
                        <canvas id="charterTypeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Locations + map --}}
            <div class="yd-card">
                <h2 class="mb-5 text-base font-semibold text-gray-900">Bookings by location</h2>
                <div class="grid grid-cols-1 items-center gap-6 md:grid-cols-[1fr_200px]">
                    <div class="space-y-3 text-sm">
                        @foreach ($locationChart as $item)
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-gray-600">{{ $item['label'] }}</span>
                                <span class="font-medium text-gray-900">{{ $item['percent'] }}%</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mx-auto w-full max-w-[200px]">
                        <svg viewBox="0 0 200 160" class="h-auto w-full" aria-hidden="true">
                            <rect width="200" height="160" rx="12" fill="#eef6f1"/>
                            <path d="M30 90 Q60 40 95 55 T150 45 T175 80 T140 120 T80 130 T30 90Z" fill="#b7e4c7" stroke="#95d5b2"/>
                            <path d="M120 50 Q145 35 165 55 T155 95 T125 105 T110 75Z" fill="#2d6a4f" opacity="0.85"/>
                            <circle cx="72" cy="88" r="5" fill="#1b4332"/>
                            <circle cx="138" cy="72" r="5" fill="#1b4332"/>
                            <text x="58" y="104" font-size="9" fill="#1b4332" font-weight="600">Dar</text>
                            <text x="126" y="88" font-size="9" fill="#1b4332" font-weight="600">Zanzibar</text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endpush

@push('scripts')
    <script>
        const chartLabels = @json($chartLabels);
        const bookingCounts = @json($bookingCounts);
        const revenueTotals = @json($revenueTotals);
        const charterTypeLabels = @json(collect($charterChart)->pluck('label'));
        const charterTypeValues = @json(collect($charterChart)->pluck('value'));

        const fallbackLabels = ['1 Jul','2 Jul','3 Jul','4 Jul','5 Jul','6 Jul','7 Jul','8 Jul','9 Jul','10 Jul','11 Jul','12 Jul'];
        const fallbackBookings = [42, 48, 38, 52, 45, 58, 50, 44, 53, 47, 55, 49];
        const fallbackRevenue = [38, 44, 35, 48, 41, 52, 46, 40, 49, 43, 50, 45];

        Chart.defaults.font.family = "'Instrument Sans', ui-sans-serif, system-ui, sans-serif";
        Chart.defaults.color = '#6b7280';

        new Chart(document.getElementById('charterChart'), {
            type: 'bar',
            data: {
                labels: chartLabels.length ? chartLabels : fallbackLabels,
                datasets: [
                    {
                        label: 'Bookings',
                        data: bookingCounts.length ? bookingCounts : fallbackBookings,
                        backgroundColor: '#4C78FF',
                        borderRadius: 3,
                        barThickness: 14,
                    },
                    {
                        label: 'Revenue',
                        data: revenueTotals.length ? revenueTotals : fallbackRevenue,
                        backgroundColor: '#FF9F43',
                        borderRadius: 3,
                        barThickness: 14,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111827', padding: 10, cornerRadius: 8 } },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 } },
                    },
                    y: {
                        grid: { color: '#f3f4f6' },
                        border: { display: false },
                        ticks: { font: { size: 11 }, callback: v => v >= 1000 ? (v/1000)+'K' : v },
                        max: 70,
                    },
                },
            },
        });

        new Chart(document.getElementById('charterTypeChart'), {
            type: 'doughnut',
            data: {
                labels: charterTypeLabels,
                datasets: [{
                    data: charterTypeValues.some(v => v > 0) ? charterTypeValues : [35, 40, 25],
                    backgroundColor: ['#4C78FF', '#FF9F43', '#9B6BFF', '#2EC4B6', '#FF6B9D'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { display: false } },
            },
        });
    </script>
@endpush
