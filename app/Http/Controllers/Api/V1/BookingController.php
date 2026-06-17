<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Requests\Api\V1\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Catamaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class BookingController extends Controller
{
    #[OA\Get(
        path: '/bookings',
        summary: 'List all bookings',
        tags: ['Bookings'],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        return BookingResource::collection(
            Booking::query()->with(['catamaran', 'extras', 'personalRequests', 'guest', 'summary'])->latest()->get()
        );
    }

    #[OA\Get(
        path: '/catamarans/{catamaranId}/bookings',
        summary: 'List bookings for a catamaran',
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'catamaranId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function indexByCatamaran(Catamaran $catamaran): AnonymousResourceCollection
    {
        return BookingResource::collection(
            $catamaran->bookings()->with(['extras', 'personalRequests', 'guest', 'summary'])->latest()->get()
        );
    }

    #[OA\Post(
        path: '/bookings',
        summary: 'Create a booking',
        tags: ['Bookings'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['catamaran_id', 'location_type', 'charter_type', 'duration', 'charter_price'],
                properties: [
                    new OA\Property(property: 'catamaran_id', type: 'integer', example: 1),
                    new OA\Property(property: 'catamaran_name', type: 'string', example: 'Ocean Spirit'),
                    new OA\Property(property: 'location_type', type: 'string', enum: ['dar_es_salaam', 'zanzibar']),
                    new OA\Property(property: 'charter_type', type: 'string', enum: ['half_day', 'full_day', 'live_onboard']),
                    new OA\Property(property: 'duration', type: 'integer', example: 4, description: 'Charter duration in hours'),
                    new OA\Property(property: 'charter_price', type: 'number', format: 'float', example: 500),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Booking created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = Booking::query()->create($request->validated());

        return BookingResource::make($booking->load('catamaran'))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/bookings/{id}',
        summary: 'Get booking details',
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function show(Booking $booking): BookingResource
    {
        $booking->load(['catamaran', 'extras', 'personalRequests', 'guest', 'summary']);

        return BookingResource::make($booking);
    }

    #[OA\Put(
        path: '/bookings/{id}',
        summary: 'Update a booking',
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'catamaran_id', type: 'integer'),
                    new OA\Property(property: 'catamaran_name', type: 'string'),
                    new OA\Property(property: 'location_type', type: 'string', enum: ['dar_es_salaam', 'zanzibar']),
                    new OA\Property(property: 'charter_type', type: 'string', enum: ['half_day', 'full_day', 'live_onboard']),
                    new OA\Property(property: 'duration', type: 'integer', description: 'Charter duration in hours'),
                    new OA\Property(property: 'charter_price', type: 'number', format: 'float'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Booking updated'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        $booking->update($request->validated());

        return BookingResource::make($booking->load(['catamaran', 'extras', 'personalRequests', 'guest', 'summary']));
    }

    #[OA\Delete(
        path: '/bookings/{id}',
        summary: 'Delete a booking',
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Booking deleted'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json(null, 204);
    }
}
