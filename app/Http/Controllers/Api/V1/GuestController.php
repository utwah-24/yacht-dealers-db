<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreGuestRequest;
use App\Http\Requests\Api\V1\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Models\Booking;
use App\Models\Guest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GuestController extends Controller
{
    #[OA\Get(
        path: '/bookings/{bookingId}/guest',
        summary: 'Get guest details for a booking',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Booking or guest not found'),
        ]
    )]
    public function show(Booking $booking): GuestResource|JsonResponse
    {
        $guest = $booking->guest;

        if (! $guest) {
            return response()->json(['message' => 'Guest not found for this booking.'], 404);
        }

        return GuestResource::make($guest);
    }

    #[OA\Post(
        path: '/bookings/{bookingId}/guest',
        summary: 'Add or replace guest details for a booking',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'date', 'phone_number', 'email', 'number_of_passengers'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Utwah Mwingira'),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-06-17'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '0613062067'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'guest@example.com'),
                    new OA\Property(property: 'number_of_passengers', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Guest saved'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function store(StoreGuestRequest $request, Booking $booking): JsonResponse
    {
        $guest = $booking->guest()->updateOrCreate(
            ['booking_id' => $booking->id],
            $request->validated()
        );

        return GuestResource::make($guest)
            ->response()
            ->setStatusCode($guest->wasRecentlyCreated ? 201 : 200);
    }

    #[OA\Put(
        path: '/guests/{id}',
        summary: 'Update guest details',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'date', type: 'string', format: 'date'),
                    new OA\Property(property: 'phone_number', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'number_of_passengers', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Guest updated'),
            new OA\Response(response: 404, description: 'Guest not found'),
        ]
    )]
    public function update(UpdateGuestRequest $request, Guest $guest): GuestResource
    {
        $guest->update($request->validated());

        return GuestResource::make($guest);
    }

    #[OA\Delete(
        path: '/guests/{id}',
        summary: 'Delete guest details',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Guest deleted'),
            new OA\Response(response: 404, description: 'Guest not found'),
        ]
    )]
    public function destroy(Guest $guest): JsonResponse
    {
        $guest->delete();

        return response()->json(null, 204);
    }
}
