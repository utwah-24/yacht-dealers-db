<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCatamaranRequest;
use App\Http\Requests\Web\BulkDeleteCatamaransRequest;
use App\Http\Requests\Web\StoreCatamaranPhotoRequest;
use App\Http\Requests\Web\UpdateCatamaranRequest;
use App\Models\Catamaran;
use Illuminate\Http\RedirectResponse;

class CatamaranController extends Controller
{
    private function redirectToCatamarans(string $message): RedirectResponse
    {
        return redirect()
            ->route('pages.catamarans', status: 303)
            ->with('success', $message);
    }

    public function store(StoreCatamaranRequest $request): RedirectResponse
    {
        Catamaran::query()->create($request->validated());

        return $this->redirectToCatamarans('Catamaran added successfully.');
    }

    public function update(UpdateCatamaranRequest $request, Catamaran $catamaran): RedirectResponse
    {
        $catamaran->update($request->validated());

        return $this->redirectToCatamarans('Catamaran updated successfully.');
    }

    public function destroy(Catamaran $catamaran): RedirectResponse
    {
        $name = $catamaran->name;
        $catamaran->delete();

        return $this->redirectToCatamarans("\"{$name}\" deleted successfully.");
    }

    public function bulkDestroy(BulkDeleteCatamaransRequest $request): RedirectResponse
    {
        $catamarans = Catamaran::query()
            ->whereIn('id', $request->validated('ids'))
            ->get();

        $count = $catamarans->count();

        foreach ($catamarans as $catamaran) {
            $catamaran->delete();
        }

        return $this->redirectToCatamarans("{$count} catamaran".($count === 1 ? '' : 's').' deleted successfully.');
    }

    public function storePhoto(StoreCatamaranPhotoRequest $request, Catamaran $catamaran): RedirectResponse
    {
        $path = $request->file('photo')->store('catamarans', 'public');

        $catamaran->photos()->create([
            'path' => $path,
            'caption' => $request->validated('caption'),
            'sort_order' => $request->validated('sort_order') ?? 0,
        ]);

        return $this->redirectToCatamarans('Photo uploaded successfully.');
    }
}
