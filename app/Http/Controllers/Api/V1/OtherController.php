<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOtherRequest;
use App\Http\Requests\Api\V1\UpdateOtherRequest;
use App\Http\Resources\OtherResource;
use App\Models\Other;
use App\Models\PersonalRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class OtherController extends Controller
{
    #[OA\Get(
        path: '/personal-requests/{personalRequestId}/others',
        summary: 'List others for a personal request',
        tags: ['Others'],
        parameters: [
            new OA\Parameter(name: 'personalRequestId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Personal request not found'),
        ]
    )]
    public function index(PersonalRequest $personalRequest): AnonymousResourceCollection
    {
        return OtherResource::collection(
            $personalRequest->others()->latest()->get()
        );
    }

    #[OA\Post(
        path: '/personal-requests/{personalRequestId}/others',
        summary: 'Add an other detail to a personal request',
        tags: ['Others'],
        parameters: [
            new OA\Parameter(name: 'personalRequestId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['type'],
                properties: [
                    new OA\Property(property: 'type', type: 'string', example: 'allergies'),
                    new OA\Property(property: 'description', type: 'string', example: 'Shellfish allergy'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Other detail created'),
            new OA\Response(response: 404, description: 'Personal request not found'),
        ]
    )]
    public function store(StoreOtherRequest $request, PersonalRequest $personalRequest): JsonResponse
    {
        $other = $personalRequest->others()->create($request->validated());

        return OtherResource::make($other)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Put(
        path: '/others/{id}',
        summary: 'Update an other detail',
        tags: ['Others'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'type', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Other detail updated'),
            new OA\Response(response: 404, description: 'Other detail not found'),
        ]
    )]
    public function update(UpdateOtherRequest $request, Other $other): OtherResource
    {
        $other->update($request->validated());

        return OtherResource::make($other);
    }

    #[OA\Delete(
        path: '/others/{id}',
        summary: 'Delete an other detail',
        tags: ['Others'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Other detail deleted'),
            new OA\Response(response: 404, description: 'Other detail not found'),
        ]
    )]
    public function destroy(Other $other): JsonResponse
    {
        $other->delete();

        return response()->json(null, 204);
    }
}
