<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePersonalRequestRequest;
use App\Http\Requests\Api\V1\UpdatePersonalRequestRequest;
use App\Http\Resources\PersonalRequestResource;
use App\Models\Booking;
use App\Models\PersonalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class PersonalRequestController extends Controller
{
    #[OA\Get(
        path: '/bookings/{bookingId}/personal-requests',
        summary: 'List personal requests for a booking',
        tags: ['Personal Requests'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function index(Booking $booking): AnonymousResourceCollection
    {
        return PersonalRequestResource::collection(
            $booking->personalRequests()->with('others')->latest()->get()
        );
    }

    #[OA\Post(
        path: '/bookings/{bookingId}/personal-requests',
        summary: 'Add a personal request to a booking',
        tags: ['Personal Requests'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['reg', 'status'],
                properties: [
                    new OA\Property(property: 'reg', type: 'string', example: 'PASS-001'),
                    new OA\Property(property: 'status', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Personal request created'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function store(StorePersonalRequestRequest $request, Booking $booking): JsonResponse
    {
        $personalRequest = $booking->personalRequests()->create($request->validated());

        return PersonalRequestResource::make($personalRequest)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Put(
        path: '/personal-requests/{id}',
        summary: 'Update a personal request',
        tags: ['Personal Requests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'reg', type: 'string'),
                    new OA\Property(property: 'status', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Personal request updated'),
            new OA\Response(response: 404, description: 'Personal request not found'),
        ]
    )]
    public function update(UpdatePersonalRequestRequest $request, PersonalRequest $personalRequest): PersonalRequestResource
    {
        $personalRequest->update($request->validated());

        return PersonalRequestResource::make($personalRequest->load('others'));
    }

    #[OA\Delete(
        path: '/personal-requests/{id}',
        summary: 'Delete a personal request',
        tags: ['Personal Requests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Personal request deleted'),
            new OA\Response(response: 404, description: 'Personal request not found'),
        ]
    )]
    public function destroy(PersonalRequest $personalRequest): JsonResponse
    {
        $personalRequest->delete();

        return response()->json(null, 204);
    }
}
