<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatamaranPhotoRequest;
use App\Http\Resources\CatamaranPhotoResource;
use App\Models\Catamaran;
use App\Models\CatamaranPhoto;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CatamaranPhotoController extends Controller
{
    #[OA\Post(
        path: '/catamarans/{catamaranId}/photos',
        summary: 'Add a photo to a catamaran',
        tags: ['Photos'],
        parameters: [
            new OA\Parameter(name: 'catamaranId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['path'],
                properties: [
                    new OA\Property(property: 'path', type: 'string', example: '/storage/catamarans/ocean-spirit.jpg'),
                    new OA\Property(property: 'caption', type: 'string', example: 'Deck view'),
                    new OA\Property(property: 'sort_order', type: 'integer', example: 0),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Photo created'),
            new OA\Response(response: 404, description: 'Catamaran not found'),
        ]
    )]
    public function store(StoreCatamaranPhotoRequest $request, Catamaran $catamaran): JsonResponse
    {
        $photo = $catamaran->photos()->create($request->validated());

        return CatamaranPhotoResource::make($photo)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Delete(
        path: '/photos/{id}',
        summary: 'Delete a catamaran photo',
        tags: ['Photos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Photo deleted'),
            new OA\Response(response: 404, description: 'Photo not found'),
        ]
    )]
    public function destroy(CatamaranPhoto $photo): JsonResponse
    {
        $photo->delete();

        return response()->json(null, 204);
    }
}
