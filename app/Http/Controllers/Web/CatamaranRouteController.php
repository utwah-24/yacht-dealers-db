<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreRouteRequest;
use App\Http\Requests\Web\UpdateRouteRequest;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;

class CatamaranRouteController extends Controller
{
    private function redirectToRoutes(string $message): RedirectResponse
    {
        return redirect()
            ->route('pages.routes', status: 303)
            ->with('success', $message);
    }

    public function store(StoreRouteRequest $request): RedirectResponse
    {
        Route::query()->create($request->validated());

        return $this->redirectToRoutes('Route added successfully.');
    }

    public function update(UpdateRouteRequest $request, Route $route): RedirectResponse
    {
        $route->update($request->validated());

        return $this->redirectToRoutes('Route updated successfully.');
    }

    public function destroy(Route $route): RedirectResponse
    {
        $departure = $route->departure;
        $route->delete();

        return $this->redirectToRoutes("\"{$departure}\" route deleted successfully.");
    }
}
