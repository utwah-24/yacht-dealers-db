@extends('layouts.dashboard')

@section('title', 'Gallery')

@section('content')
    <x-page-header title="Gallery" description="Add, arrange, and remove yacht photos from one place.">
        <x-slot:actions>
            <button type="button" class="yd-btn-primary" data-modal-open="add-gallery-photo-modal">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Add photo
            </button>
        </x-slot:actions>

        @if (session('success'))
            <div class="yd-alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="space-y-7" id="gallery-sections">
            @forelse ($catamarans->filter(fn ($catamaran) => $catamaran->photos->isNotEmpty()) as $catamaran)
                <section class="gallery-section rounded-2xl border border-yd-line bg-white p-5" data-catamaran-id="{{ $catamaran->id }}">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">{{ $catamaran->name }}</h2>
                            <p class="mt-1 text-xs text-gray-500">{{ $catamaran->photos_count }} {{ Str::plural('picture', $catamaran->photos_count) }}</p>
                        </div>
                        @if ($catamaran->photos->isNotEmpty())
                            <button type="submit" form="order-form-{{ $catamaran->id }}" class="yd-btn-secondary">Save order</button>
                        @endif
                    </div>

                    <form id="order-form-{{ $catamaran->id }}" method="POST" action="{{ route('pages.gallery.photos.reorder') }}">
                            @csrf
                            @method('PATCH')
                            <div class="gallery-grid grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" data-gallery-grid>
                                @foreach ($catamaran->photos as $photo)
                                    <article class="gallery-card group overflow-hidden rounded-xl border border-yd-line bg-white shadow-sm" draggable="true" data-photo-id="{{ $photo->id }}">
                                        <input type="hidden" name="photo_ids[]" value="{{ $photo->id }}">
                                        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                                            <img src="{{ $photo->url }}" alt="{{ $photo->caption ?? $catamaran->name }}" class="h-full w-full object-cover transition duration-200 group-hover:scale-[1.02]">
                                            <span class="absolute top-2 left-2 cursor-grab rounded-lg bg-black/60 px-2 py-1 text-xs font-medium text-white" title="Drag to arrange">Drag</span>
                                        </div>
                                        <div class="p-3">
                                            <p class="truncate text-sm font-medium text-gray-900">{{ $photo->caption ?: 'Untitled picture' }}</p>
                                            <p class="mt-1 text-xs text-gray-400">Position <span data-position>{{ $loop->iteration }}</span></p>
                                            <div class="mt-3 flex items-center justify-between">
                                                <div class="flex gap-1">
                                                    <button type="button" class="yd-icon-btn move-photo" data-direction="up" title="Move picture left" aria-label="Move picture left">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                                    </button>
                                                    <button type="button" class="yd-icon-btn move-photo" data-direction="down" title="Move picture right" aria-label="Move picture right">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                    </button>
                                                </div>
                                                <button type="button" class="yd-icon-btn yd-icon-btn-danger delete-gallery-photo" data-delete-url="{{ route('pages.gallery.photos.destroy', $photo) }}" data-photo-name="{{ $photo->caption ?: 'this picture' }}" title="Delete picture" aria-label="Delete picture">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                    </form>
                </section>
            @empty
                <div class="rounded-2xl border border-yd-line bg-white px-6 py-14 text-center shadow-sm">
                    <svg class="mx-auto h-10 w-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.5-4.5a2 2 0 012.8 0L16 16m-2-2 1.5-1.5a2 2 0 012.8 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="mt-3 text-sm font-medium text-gray-700">No gallery pictures yet</p>
                    <p class="mt-1 text-sm text-gray-500">Use Add photo to upload the first picture.</p>
                </div>
            @endforelse
        </div>
    </x-page-header>

    <x-modal id="add-gallery-photo-modal" title="Add gallery picture">
        <form method="POST" action="" id="gallery-upload-form" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="gallery_catamaran_id" class="yd-label">Catamaran</label>
                <select id="gallery_catamaran_id" name="catamaran_id" class="yd-input" required {{ $catamarans->isEmpty() ? 'disabled' : '' }}>
                    <option value="">Select catamaran</option>
                    @foreach ($catamarans as $catamaran)
                        <option value="{{ $catamaran->id }}" @selected(old('catamaran_id') == $catamaran->id)>{{ $catamaran->name }}</option>
                    @endforeach
                </select>
                @if ($catamarans->isEmpty())
                    <p class="mt-1.5 text-xs text-amber-600">A catamaran must exist before a photo can be added.</p>
                @endif
            </div>
            <div>
                <label for="gallery_photo" class="yd-label">Picture</label>
                <input id="gallery_photo" name="photo" type="file" accept="image/jpeg,image/png,image/gif,image/webp" class="yd-file-input" required>
                <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, GIF, or WebP — max 5 MB</p>
            </div>
            <div>
                <label for="gallery_caption" class="yd-label">Caption <span class="font-normal text-gray-400">(optional)</span></label>
                <input id="gallery_caption" name="caption" type="text" value="{{ old('caption') }}" class="yd-input" placeholder="Sunset deck view">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="add-gallery-photo-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary" {{ $catamarans->isEmpty() ? 'disabled' : '' }}>Add picture</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="delete-gallery-photo-modal" title="Delete picture">
        <form method="POST" action="" id="delete-gallery-photo-form">
            @csrf
            @method('DELETE')
            <p class="text-sm text-gray-600">Delete <span id="delete-gallery-photo-name" class="font-semibold text-gray-900"></span>? This cannot be undone.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="yd-btn-secondary" data-modal-close="delete-gallery-photo-modal">Cancel</button>
                <button type="submit" class="yd-btn-danger">Delete picture</button>
            </div>
        </form>
    </x-modal>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const uploadForm = document.getElementById('gallery-upload-form');
            const catamaranSelect = document.getElementById('gallery_catamaran_id');
            const uploadTemplate = @json(route('pages.gallery.photos.store', ['catamaran' => '__ID__']));
            const openModal = id => {
                const modal = document.getElementById(id);
                modal?.classList.remove('hidden');
                modal?.classList.add('flex');
            };
            const closeModal = id => {
                const modal = document.getElementById(id);
                modal?.classList.add('hidden');
                modal?.classList.remove('flex');
            };

            document.querySelectorAll('[data-modal-open]').forEach(button => button.addEventListener('click', () => openModal(button.dataset.modalOpen)));
            document.querySelectorAll('[data-modal-close]').forEach(button => button.addEventListener('click', () => closeModal(button.dataset.modalClose)));
            document.querySelectorAll('[role="dialog"]').forEach(modal => modal.addEventListener('click', event => {
                if (event.target === modal) closeModal(modal.id);
            }));

            const syncUploadAction = () => {
                if (catamaranSelect?.value) uploadForm.action = uploadTemplate.replace('__ID__', catamaranSelect.value);
            };
            catamaranSelect?.addEventListener('change', syncUploadAction);
            syncUploadAction();

            const updatePositions = grid => grid.querySelectorAll('.gallery-card').forEach((card, index) => {
                card.querySelector('[data-position]').textContent = index + 1;
            });

            document.querySelectorAll('[data-gallery-grid]').forEach(grid => {
                let dragged = null;
                grid.querySelectorAll('.gallery-card').forEach(card => {
                    card.addEventListener('dragstart', () => { dragged = card; card.classList.add('opacity-50'); });
                    card.addEventListener('dragend', () => { card.classList.remove('opacity-50'); dragged = null; updatePositions(grid); });
                    card.addEventListener('dragover', event => {
                        event.preventDefault();
                        if (!dragged || dragged === card) return;
                        const box = card.getBoundingClientRect();
                        grid.insertBefore(dragged, event.clientX < box.left + box.width / 2 ? card : card.nextSibling);
                    });
                });
            });

            document.querySelectorAll('.move-photo').forEach(button => button.addEventListener('click', () => {
                const card = button.closest('.gallery-card');
                const grid = card.parentElement;
                if (button.dataset.direction === 'up' && card.previousElementSibling) grid.insertBefore(card, card.previousElementSibling);
                if (button.dataset.direction === 'down' && card.nextElementSibling) grid.insertBefore(card.nextElementSibling, card);
                updatePositions(grid);
            }));

            document.querySelectorAll('.delete-gallery-photo').forEach(button => button.addEventListener('click', () => {
                document.getElementById('delete-gallery-photo-form').action = button.dataset.deleteUrl;
                document.getElementById('delete-gallery-photo-name').textContent = button.dataset.photoName;
                openModal('delete-gallery-photo-modal');
            }));

            @if ($errors->any()) openModal('add-gallery-photo-modal'); @endif
        });
    </script>
@endpush
