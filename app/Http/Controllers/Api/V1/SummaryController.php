<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSummaryRequest;
use App\Http\Requests\Api\V1\UpdateSummaryRequest;
use App\Http\Resources\SummaryResource;
use App\Models\Booking;
use App\Models\Summary;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SummaryController extends Controller
{
    #[OA\Get(
        path: '/bookings/{bookingId}/summary',
        summary: 'Get order summary for a booking',
        tags: ['Summaries'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Booking or summary not found'),
        ]
    )]
    public function show(Booking $booking): SummaryResource|JsonResponse
    {
        $summary = $booking->summary()->with(['guest', 'photo'])->first();

        if (! $summary) {
            return response()->json(['message' => 'Summary not found for this booking.'], 404);
        }

        return SummaryResource::make($summary);
    }

    #[OA\Post(
        path: '/bookings/{bookingId}/summary',
        summary: 'Create or replace order summary for a booking',
        tags: ['Summaries'],
        parameters: [
            new OA\Parameter(name: 'bookingId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['catamaran_photo_id'],
                properties: [
                    new OA\Property(property: 'guest_id', type: 'integer', example: 1),
                    new OA\Property(property: 'catamaran_photo_id', type: 'integer', example: 1, description: 'Foreign key to catamaran_photos'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Summary saved'),
            new OA\Response(response: 404, description: 'Booking not found'),
        ]
    )]
    public function store(StoreSummaryRequest $request, Booking $booking): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['guest_id']) && $booking->guest) {
            $data['guest_id'] = $booking->guest->id;
        }

        $summary = $booking->summary()->updateOrCreate(
            ['booking_id' => $booking->id],
            $data
        );

        return SummaryResource::make($summary->load(['guest', 'photo']))
            ->response()
            ->setStatusCode($summary->wasRecentlyCreated ? 201 : 200);
    }

    #[OA\Put(
        path: '/summaries/{id}',
        summary: 'Update order summary',
        tags: ['Summaries'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'guest_id', type: 'integer'),
                    new OA\Property(property: 'catamaran_photo_id', type: 'integer', description: 'Foreign key to catamaran_photos'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Summary updated'),
            new OA\Response(response: 404, description: 'Summary not found'),
        ]
    )]
    public function update(UpdateSummaryRequest $request, Summary $summary): SummaryResource
    {
        $summary->update($request->validated());

        return SummaryResource::make($summary->load(['guest', 'photo']));
    }

    #[OA\Delete(
        path: '/summaries/{id}',
        summary: 'Delete order summary',
        tags: ['Summaries'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Summary deleted'),
            new OA\Response(response: 404, description: 'Summary not found'),
        ]
    )]
    public function destroy(Summary $summary): JsonResponse
    {
        $summary->delete();

        return response()->json(null, 204);
    }
}
