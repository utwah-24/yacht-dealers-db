<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreCatamaranPhotoRequest;
use App\Models\Catamaran;
use App\Models\CatamaranPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function store(StoreCatamaranPhotoRequest $request, Catamaran $catamaran): RedirectResponse
    {
        $path = $request->file('photo')->store('catamarans', 'public');

        $catamaran->photos()->create([
            'path' => $path,
            'caption' => $request->validated('caption'),
            'sort_order' => $request->validated('sort_order') ?? $catamaran->photos()->count(),
        ]);

        return redirect()->route('pages.gallery', status: 303)->with('success', 'Photo added to the gallery.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'photo_ids' => ['required', 'array'],
            'photo_ids.*' => ['required', 'integer', 'distinct', 'exists:catamaran_photos,id'],
        ]);

        $photos = CatamaranPhoto::query()->whereIn('id', $validated['photo_ids'])->get()->keyBy('id');
        $catamaranIds = $photos->pluck('catamaran_id')->unique();

        if ($catamaranIds->count() !== 1 || $photos->count() !== count($validated['photo_ids'])) {
            return back()->withErrors(['photo_ids' => 'Only photos from the same catamaran can be arranged together.']);
        }

        DB::transaction(function () use ($validated): void {
            foreach ($validated['photo_ids'] as $position => $photoId) {
                CatamaranPhoto::query()->whereKey($photoId)->update(['sort_order' => $position]);
            }
        });

        return redirect()->route('pages.gallery', status: 303)->with('success', 'Gallery order saved.');
    }

    public function destroy(CatamaranPhoto $photo): RedirectResponse
    {
        $photo->delete();

        return redirect()->route('pages.gallery', status: 303)->with('success', 'Photo deleted from the gallery.');
    }
}
