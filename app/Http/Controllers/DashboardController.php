<?php

namespace App\Http\Controllers;

use App\Enums\CharterType;
use App\Enums\LocationType;
use App\Models\Booking;
use App\Models\Catamaran;
use App\Models\Guest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBookings = Booking::query()->count();
        $totalCatamarans = Catamaran::query()->count();
        $totalGuests = Guest::query()->count();
        $totalPassengers = (int) Guest::query()->sum('number_of_passengers');
        $totalRevenue = (float) Booking::query()->sum('charter_price');

        $previousMonthBookings = Booking::query()
            ->where('created_at', '<', now()->subMonth())
            ->count();

        $bookingTrend = $previousMonthBookings > 0
            ? round((($totalBookings - $previousMonthBookings) / $previousMonthBookings) * 100, 1)
            : ($totalBookings > 0 ? 100 : 0);

        $revenuePrevious = (float) Booking::query()
            ->where('created_at', '<', now()->subMonth())
            ->sum('charter_price');

        $revenueTrend = $revenuePrevious > 0
            ? round((($totalRevenue - $revenuePrevious) / $revenuePrevious) * 100, 1)
            : ($totalRevenue > 0 ? 100 : 0);

        $charterChart = Booking::query()
            ->select('charter_type', DB::raw('count(*) as total'))
            ->groupBy('charter_type')
            ->pluck('total', 'charter_type')
            ->all();

        $locationChart = Booking::query()
            ->select('location_type', DB::raw('count(*) as total'))
            ->groupBy('location_type')
            ->pluck('total', 'location_type')
            ->all();

        $monthlyBookings = Booking::query()
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('count(*) as bookings'),
                DB::raw('sum(charter_price) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(11))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $chartLabels = $monthlyBookings->map(
            fn ($row) => Carbon::parse($row->day)->format('j M')
        )->values();

        $recentBookings = Booking::query()
            ->with('catamaran')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'stats' => [
                'guests' => [
                    'value' => number_format($totalGuests),
                    'sub' => $totalPassengers.' passengers',
                    'trend' => $bookingTrend,
                    'up' => $bookingTrend >= 0,
                ],
                'revenue' => [
                    'value' => '$'.number_format($totalRevenue, 0),
                    'sub' => 'Charter revenue',
                    'trend' => $revenueTrend,
                    'up' => $revenueTrend >= 0,
                ],
                'bookings' => [
                    'value' => number_format($totalBookings),
                    'sub' => 'All charters',
                    'trend' => $bookingTrend,
                    'up' => $bookingTrend >= 0,
                ],
                'catamarans' => [
                    'value' => number_format($totalCatamarans),
                    'sub' => 'Active fleet',
                    'trend' => 0,
                    'up' => true,
                ],
            ],
            'charterChart' => $this->formatEnumChart($charterChart, CharterType::class),
            'locationChart' => $this->formatEnumChart($locationChart, LocationType::class),
            'chartLabels' => $chartLabels,
            'bookingCounts' => $monthlyBookings->pluck('bookings')->values(),
            'revenueTotals' => $monthlyBookings->pluck('revenue')->values(),
            'recentBookings' => $recentBookings,
        ]);
    }

    /**
     * @param  array<string|int, int>  $data
     * @param  class-string  $enumClass
     * @return array<int, array{label: string, value: int, percent: float}>
     */
    private function formatEnumChart(array $data, string $enumClass): array
    {
        $total = array_sum($data) ?: 1;
        $result = [];

        foreach ($enumClass::cases() as $case) {
            $value = (int) ($data[$case->value] ?? 0);
            $result[] = [
                'label' => $case->label(),
                'value' => $value,
                'percent' => round(($value / $total) * 100, 1),
            ];
        }

        return $result;
    }
}
