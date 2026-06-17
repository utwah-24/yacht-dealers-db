<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatamaranRouteRequest;
use App\Http\Requests\Api\V1\UpdateCatamaranRouteRequest;
use App\Http\Resources\CatamaranRouteResource;
use App\Models\Catamaran;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CatamaranRouteController extends Controller
{
    #[OA\Post(
        path: '/catamarans/{catamaranId}/routes',
        summary: 'Add a route to a catamaran',
        tags: ['Routes'],
        parameters: [
            new OA\Parameter(name: 'catamaranId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['departure', 'destinations'],
                properties: [
                    new OA\Property(property: 'departure', type: 'string', example: 'Dar es Salaam Marina'),
                    new OA\Property(
                        property: 'destinations',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['Bongoyo Island', 'Mbudya Island']
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Route created'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function store(StoreCatamaranRouteRequest $request, Catamaran $catamaran): JsonResponse
    {
        $route = $catamaran->routes()->create($request->validated());

        return CatamaranRouteResource::make($route)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Put(
        path: '/routes/{id}',
        summary: 'Update a catamaran route',
        tags: ['Routes'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'departure', type: 'string'),
                    new OA\Property(property: 'destinations', type: 'array', items: new OA\Items(type: 'string')),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Route updated'),
            new OA\Response(response: 404, description: 'Route not found'),
        ]
    )]
    public function update(UpdateCatamaranRouteRequest $request, Route $route): CatamaranRouteResource
    {
        $route->update($request->validated());

        return CatamaranRouteResource::make($route);
    }

    #[OA\Delete(
        path: '/routes/{id}',
        summary: 'Delete a catamaran route',
        tags: ['Routes'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Route deleted'),
            new OA\Response(response: 404, description: 'Route not found'),
        ]
    )]
    public function destroy(Route $route): JsonResponse
    {
        $route->delete();

        return response()->json(null, 204);
    }
}
