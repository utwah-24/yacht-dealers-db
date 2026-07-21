<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatamaranPhotoRequest;
use App\Http\Resources\CatamaranPhotoResource;
use App\Models\Catamaran;
use App\Models\CatamaranPhoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class CatamaranPhotoController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CatamaranPhotoResource::collection(
            CatamaranPhoto::query()->with('catamaran')->orderBy('catamaran_id')->orderBy('sort_order')->get()
        );
    }

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

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'photo_ids' => ['required', 'array'],
            'photo_ids.*' => ['required', 'integer', 'distinct', 'exists:catamaran_photos,id'],
        ]);

        $photos = CatamaranPhoto::query()->whereIn('id', $validated['photo_ids'])->get();

        if ($photos->pluck('catamaran_id')->unique()->count() !== 1 || $photos->count() !== count($validated['photo_ids'])) {
            return response()->json(['message' => 'Only photos from the same catamaran can be arranged together.'], 422);
        }

        DB::transaction(function () use ($validated): void {
            foreach ($validated['photo_ids'] as $position => $photoId) {
                CatamaranPhoto::query()->whereKey($photoId)->update(['sort_order' => $position]);
            }
        });

        return response()->json(['message' => 'Gallery order updated.']);
    }
}
