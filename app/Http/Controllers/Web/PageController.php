<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Catamaran;
use App\Models\CatamaranPhoto;
use App\Models\Guest;
use App\Models\Route;
use App\Models\Summary;
use Illuminate\View\View;

class PageController extends Controller
{
    public function catamarans(): View
    {
        $catamarans = Catamaran::query()
            ->withCount(['photos', 'routes', 'bookings'])
            ->latest()
            ->get();

        $photos = CatamaranPhoto::query()
            ->with('catamaran')
            ->orderBy('catamaran_id')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('pages.catamarans', compact('catamarans', 'photos'));
    }

    public function gallery(): View
    {
        $catamarans = Catamaran::query()
            ->with(['photos' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')])
            ->withCount('photos')
            ->orderBy('name')
            ->get();

        return view('pages.gallery', compact('catamarans'));
    }

    public function bookings(): View
    {
        $bookings = Booking::query()
            ->with(['catamaran', 'guest', 'extras'])
            ->latest()
            ->get();

        return view('pages.bookings', compact('bookings'));
    }

    public function guests(): View
    {
        $guests = Guest::query()
            ->with('booking')
            ->latest()
            ->get();

        return view('pages.guests', compact('guests'));
    }

    public function routes(): View
    {
        $routes = Route::query()
            ->with('catamaran')
            ->latest()
            ->get();

        $catamarans = Catamaran::query()
            ->orderBy('name')
            ->get();

        return view('pages.routes', compact('routes', 'catamarans'));
    }

    public function summaries(): View
    {
        $summaries = Summary::query()
            ->with(['booking', 'guest', 'photo.catamaran'])
            ->latest()
            ->get();

        return view('pages.summaries', compact('summaries'));
    }

    public function settings(): View
    {
        return view('pages.settings');
    }
}
