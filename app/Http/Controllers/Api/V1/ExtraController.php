<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreExtraRequest;
use App\Http\Resources\ExtraResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ExtraController extends Controller
{
    #[OA\Post(
        path: '/bookings/{bookingId}/extras',
        summary: 'Add or replace extras for a booking',
        tags: ['Extras'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['additional_services', 'additional_cost', 'status'],
                properties: [
                    new OA\Property(property: 'additional_services', type: 'string', example: 'Snorkeling gear rental'),
                    new OA\Property(property: 'additional_cost', type: 'number', format: 'float', example: 75),
                    new OA\Property(property: 'status', type: 'boolean', example: true),
                    new OA\Property(property: 'description', type: 'string', example: 'Full snorkeling set for 4 guests'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Extras saved'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function store(StoreExtraRequest $request, Booking $booking): JsonResponse
    {
        $extra = $booking->extras()->updateOrCreate(
            ['booking_id' => $booking->id],
            $request->validated()
        );

        return ExtraResource::make($extra)
            ->response()
            ->setStatusCode($extra->wasRecentlyCreated ? 201 : 200);
    }
}
