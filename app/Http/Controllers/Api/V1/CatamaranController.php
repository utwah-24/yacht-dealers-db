<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatamaranRequest;
use App\Http\Requests\Api\V1\UpdateCatamaranRequest;
use App\Http\Resources\CatamaranResource;
use App\Models\Catamaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CatamaranController extends Controller
{
    #[OA\Get(
        path: '/catamarans',
        summary: 'List all catamarans',
        tags: ['Catamarans'],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        return CatamaranResource::collection(
            Catamaran::query()->with(['photos', 'routes', 'bookings'])->latest()->get()
        );
    }

    #[OA\Post(
        path: '/catamarans',
        summary: 'Create a catamaran',
        tags: ['Catamarans'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'services_provided', 'description'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Ocean Spirit'),
                    new OA\Property(property: 'services_provided', type: 'string', example: 'Snorkeling, fishing'),
                    new OA\Property(property: 'description', type: 'string', example: 'Luxury 45ft catamaran'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Catamaran created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreCatamaranRequest $request): JsonResponse
    {
        $catamaran = Catamaran::query()->create($request->validated());

        return CatamaranResource::make($catamaran)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/catamarans/{id}',
        summary: 'Get catamaran details',
        tags: ['Catamarans'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Successful response'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function show(Catamaran $catamaran): CatamaranResource
    {
        $catamaran->load(['photos', 'routes', 'bookings']);

        return CatamaranResource::make($catamaran);
    }

    #[OA\Put(
        path: '/catamarans/{id}',
        summary: 'Update a catamaran',
        tags: ['Catamarans'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'services_provided', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Catamaran updated'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function update(UpdateCatamaranRequest $request, Catamaran $catamaran): CatamaranResource
    {
        $catamaran->update($request->validated());

        return CatamaranResource::make($catamaran->load(['photos', 'routes', 'bookings']));
    }

    #[OA\Delete(
        path: '/catamarans/{id}',
        summary: 'Delete a catamaran',
        tags: ['Catamarans'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Catamaran deleted'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function destroy(Catamaran $catamaran): JsonResponse
    {
        $catamaran->delete();

        return response()->json(null, 204);
    }
}
