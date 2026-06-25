@extends('layouts.dashboard')

@section('title', 'Catamarans')

@section('content')
    <x-page-header title="Catamarans" description="Yacht fleet catalog — name, services, description, photos, routes, and bookings.">
        <x-slot:actions>
            <button type="button" class="yd-btn-secondary" data-modal-open="add-photo-modal" {{ $catamarans->isEmpty() ? 'disabled' : '' }}>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Add photo
            </button>
            <button type="button" class="yd-btn-primary" data-modal-open="add-catamaran-modal">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                </svg>
                Add catamaran
            </button>
        </x-slot:actions>

        @if (session('success'))
            <div class="yd-alert-success" id="success-alert">{{ session('success') }}</div>
        @endif

        <form id="catamarans-bulk-form" method="POST" action="{{ route('pages.catamarans.bulk-destroy') }}" class="hidden" aria-hidden="true">
            @csrf
            @method('DELETE')
            <div id="bulk-delete-ids-container"></div>
        </form>

        <x-data-table :count="$catamarans->count()" title="catamarans">
                <x-slot:toolbar>
                    <div id="catamarans-bulk-toolbar" class="yd-bulk-toolbar">
                        <span id="catamarans-selected-count">0 selected</span>
                        <button type="button" class="yd-btn-danger" data-modal-open="bulk-delete-modal">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete selected
                        </button>
                    </div>
                </x-slot:toolbar>
                <x-slot:head>
                    <th class="w-10">
                        <input type="checkbox" id="catamarans-select-all" class="yd-checkbox" aria-label="Select all catamarans" {{ $catamarans->isEmpty() ? 'disabled' : '' }}>
                    </th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Services provided</th>
                    <th>Description</th>
                    <th>Photos</th>
                    <th>Routes</th>
                    <th>Bookings</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th class="w-24 text-right">Actions</th>
                </x-slot:head>
                <x-slot:body>
                    @forelse ($catamarans as $catamaran)
                        <tr>
                            <td>
                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $catamaran->id }}"
                                    class="yd-checkbox catamaran-row-checkbox"
                                    aria-label="Select {{ $catamaran->name }}"
                                >
                            </td>
                            <td>{{ $catamaran->id }}</td>
                            <td class="font-medium text-gray-900">{{ $catamaran->name }}</td>
                            <td class="max-w-[200px] whitespace-normal">{{ $catamaran->services_provided }}</td>
                            <td class="max-w-[240px] whitespace-normal text-gray-600">{{ $catamaran->description }}</td>
                            <td>{{ $catamaran->photos_count }}</td>
                            <td>{{ $catamaran->routes_count }}</td>
                            <td>{{ $catamaran->bookings_count }}</td>
                            <td class="whitespace-nowrap text-gray-500">{{ $catamaran->created_at?->format('M j, Y g:i A') }}</td>
                            <td class="whitespace-nowrap text-gray-500">{{ $catamaran->updated_at?->format('M j, Y g:i A') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-0.5">
                                    <button
                                        type="button"
                                        class="yd-icon-btn edit-catamaran-btn"
                                        title="Edit {{ $catamaran->name }}"
                                        data-catamaran-id="{{ $catamaran->id }}"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="yd-icon-btn yd-icon-btn-danger"
                                        title="Delete {{ $catamaran->name }}"
                                        data-modal-open="delete-catamaran-modal"
                                        data-catamaran-name="{{ $catamaran->name }}"
                                        data-delete-url="{{ route('pages.catamarans.destroy', $catamaran) }}"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-10 text-center text-gray-500">
                                No catamarans yet. Click <span class="font-medium text-gray-700">Add catamaran</span> to create your first fleet entry.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
        </x-data-table>

        <div class="mt-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Catamaran photos</h2>
                    <p class="mt-1 text-sm text-gray-500">Records from the <span class="font-medium">catamaran_photos</span> table — path, caption, and sort order per yacht.</p>
                </div>
            </div>

            <x-data-table :count="$photos->count()" title="catamaran_photos">
                <x-slot:head>
                    <th>ID</th>
                    <th>Catamaran ID</th>
                    <th>Catamaran</th>
                    <th>Preview</th>
                    <th>Path</th>
                    <th>Caption</th>
                    <th>Sort order</th>
                    <th>Created</th>
                    <th>Updated</th>
                </x-slot:head>
                <x-slot:body>
                    @forelse ($photos as $photo)
                        <tr>
                            <td>{{ $photo->id }}</td>
                            <td>{{ $photo->catamaran_id }}</td>
                            <td class="font-medium text-gray-900">{{ $photo->catamaran?->name ?? '—' }}</td>
                            <td>
                                <img
                                    src="{{ $photo->url }}"
                                    alt="{{ $photo->caption ?? 'Catamaran photo' }}"
                                    class="h-12 w-16 rounded-lg border border-yd-line object-cover"
                                >
                            </td>
                            <td class="max-w-[220px] truncate font-mono text-xs text-gray-600" title="{{ $photo->path }}">{{ $photo->path }}</td>
                            <td>{{ $photo->caption ?? '—' }}</td>
                            <td>{{ $photo->sort_order }}</td>
                            <td class="whitespace-nowrap text-gray-500">{{ $photo->created_at?->format('M j, Y g:i A') }}</td>
                            <td class="whitespace-nowrap text-gray-500">{{ $photo->updated_at?->format('M j, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-gray-500">
                                No photos yet. Add a catamaran first, then use <span class="font-medium text-gray-700">Add photo</span>.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:body>
            </x-data-table>
        </div>
    </x-page-header>

    <x-modal id="add-catamaran-modal" title="Add catamaran">
        <form method="POST" action="{{ route('pages.catamarans.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="yd-label">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="yd-input @error('name') border-red-300 ring-red-100 @enderror"
                    placeholder="Ocean Spirit"
                    required
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="services_provided" class="yd-label">Services provided</label>
                <textarea
                    id="services_provided"
                    name="services_provided"
                    rows="3"
                    class="yd-input @error('services_provided') border-red-300 ring-red-100 @enderror"
                    placeholder="Snorkeling, fishing, sunset cruise"
                    required
                >{{ old('services_provided') }}</textarea>
                @error('services_provided')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="yd-label">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="yd-input @error('description') border-red-300 ring-red-100 @enderror"
                    placeholder="Luxury 45ft catamaran for Dar es Salaam and Zanzibar charters."
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="add-catamaran-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary">Save catamaran</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="add-photo-modal" title="Add catamaran photo">
        <form method="POST" action="" id="add-photo-form" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="photo_catamaran_id" class="yd-label">Catamaran</label>
                <select
                    id="photo_catamaran_id"
                    name="catamaran_id"
                    class="yd-input @error('catamaran_id') border-red-300 ring-red-100 @enderror"
                    required
                >
                    <option value="">Select catamaran</option>
                    @foreach ($catamarans as $catamaran)
                        <option value="{{ $catamaran->id }}" @selected(old('catamaran_id') == $catamaran->id)>
                            {{ $catamaran->name }}
                        </option>
                    @endforeach
                </select>
                @error('catamaran_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="photo" class="yd-label">Picture</label>
                <input
                    id="photo"
                    name="photo"
                    type="file"
                    accept="image/jpeg,image/png,image/gif,image/webp"
                    class="yd-file-input @error('photo') border-red-300 @enderror"
                    required
                >
                <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, GIF, or WebP — max 5 MB</p>
                @error('photo')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="caption" class="yd-label">Caption <span class="font-normal text-gray-400">(optional)</span></label>
                <input
                    id="caption"
                    name="caption"
                    type="text"
                    value="{{ old('caption') }}"
                    class="yd-input @error('caption') border-red-300 ring-red-100 @enderror"
                    placeholder="Deck view at sunset"
                >
                @error('caption')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="yd-label">Sort order <span class="font-normal text-gray-400">(optional)</span></label>
                <input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    value="{{ old('sort_order', 0) }}"
                    class="yd-input @error('sort_order') border-red-300 ring-red-100 @enderror"
                >
                @error('sort_order')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="add-photo-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary">Save photo</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="edit-catamaran-modal" title="Edit catamaran">
        <form method="POST" action="{{ old('_update_url', '#') }}" id="edit-catamaran-form" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="_update_url" id="edit_update_url" value="{{ old('_update_url') }}">

            <div>
                <label for="edit_name" class="yd-label">Name</label>
                <input
                    id="edit_name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    class="yd-input @error('name') border-red-300 ring-red-100 @enderror"
                    required
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="edit_services_provided" class="yd-label">Services provided</label>
                <textarea
                    id="edit_services_provided"
                    name="services_provided"
                    rows="3"
                    class="yd-input @error('services_provided') border-red-300 ring-red-100 @enderror"
                    required
                >{{ old('services_provided') }}</textarea>
                @error('services_provided')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="edit_description" class="yd-label">Description</label>
                <textarea
                    id="edit_description"
                    name="description"
                    rows="4"
                    class="yd-input @error('description') border-red-300 ring-red-100 @enderror"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="edit-catamaran-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary">Save changes</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="delete-catamaran-modal" title="Delete catamaran">
        <p class="text-sm text-gray-600">
            Are you sure you want to delete <span id="delete-catamaran-name" class="font-semibold text-gray-900"></span>?
            This will also remove related photos, routes, and bookings.
        </p>
        <form method="POST" action="" id="delete-catamaran-form" class="mt-6 flex justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button" class="yd-btn-secondary" data-modal-close="delete-catamaran-modal">Cancel</button>
            <button type="submit" class="yd-btn-danger">Delete</button>
        </form>
    </x-modal>

    <x-modal id="bulk-delete-modal" title="Delete selected catamarans">
        <p class="text-sm text-gray-600">
            Are you sure you want to delete <span id="bulk-delete-count" class="font-semibold text-gray-900">0</span> selected catamaran(s)?
            Related photos, routes, and bookings will also be removed.
        </p>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="yd-btn-secondary" data-modal-close="bulk-delete-modal">Cancel</button>
            <button type="button" class="yd-btn-danger" id="confirm-bulk-delete">Delete selected</button>
        </div>
    </x-modal>

    <script type="application/json" id="catamarans-edit-data">
        {!! json_encode($catamarans->mapWithKeys(fn ($catamaran) => [
            $catamaran->id => [
                'name' => $catamaran->name,
                'services_provided' => $catamaran->services_provided,
                'description' => $catamaran->description,
                'update_url' => route('pages.catamarans.update', $catamaran),
            ],
        ]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const catamaransEditData = (() => {
                const source = document.getElementById('catamarans-edit-data');
                if (! source) return {};

                try {
                    return JSON.parse(source.textContent || '{}');
                } catch (error) {
                    return {};
                }
            })();

            const openModal = (id) => {
                const modal = document.getElementById(id);
                if (! modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = (id) => {
                const modal = document.getElementById(id);
                if (! modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            const closeAllModals = () => {
                document.querySelectorAll('[role="dialog"]').forEach((modal) => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            };

            document.querySelectorAll('[data-modal-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modalId = button.dataset.modalOpen;

                    if (modalId === 'delete-catamaran-modal') {
                        document.getElementById('delete-catamaran-name').textContent = button.dataset.catamaranName;
                        document.getElementById('delete-catamaran-form').action = button.dataset.deleteUrl;
                    }

                    if (modalId === 'bulk-delete-modal') {
                        const selected = document.querySelectorAll('.catamaran-row-checkbox:checked').length;
                        document.getElementById('bulk-delete-count').textContent = String(selected);
                    }

                    openModal(modalId);
                });
            });

            document.querySelectorAll('.edit-catamaran-btn').forEach((button) => {
                button.addEventListener('click', () => {
                    const catamaran = catamaransEditData[button.dataset.catamaranId];
                    if (! catamaran) return;

                    const form = document.getElementById('edit-catamaran-form');
                    form.action = catamaran.update_url;
                    document.getElementById('edit_update_url').value = catamaran.update_url;
                    document.getElementById('edit_name').value = catamaran.name;
                    document.getElementById('edit_services_provided').value = catamaran.services_provided;
                    document.getElementById('edit_description').value = catamaran.description;

                    openModal('edit-catamaran-modal');
                });
            });

            document.querySelectorAll('[data-modal-close]').forEach((button) => {
                button.addEventListener('click', () => closeModal(button.dataset.modalClose));
            });

            document.querySelectorAll('[role="dialog"]').forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });

            const bulkToolbar = document.getElementById('catamarans-bulk-toolbar');
            const selectedCount = document.getElementById('catamarans-selected-count');
            const selectAll = document.getElementById('catamarans-select-all');
            const rowCheckboxes = () => Array.from(document.querySelectorAll('.catamaran-row-checkbox'));

            const syncBulkSelection = () => {
                const boxes = rowCheckboxes();
                const checked = boxes.filter((box) => box.checked);
                const count = checked.length;

                if (selectedCount) {
                    selectedCount.textContent = `${count} selected`;
                }

                if (bulkToolbar) {
                    bulkToolbar.classList.toggle('is-visible', count > 0);
                }

                if (selectAll && boxes.length > 0) {
                    selectAll.checked = count === boxes.length;
                    selectAll.indeterminate = count > 0 && count < boxes.length;
                }
            };

            selectAll?.addEventListener('change', () => {
                rowCheckboxes().forEach((box) => {
                    box.checked = selectAll.checked;
                });
                syncBulkSelection();
            });

            rowCheckboxes().forEach((box) => {
                box.addEventListener('change', syncBulkSelection);
            });

            const resetPageState = () => {
                closeAllModals();
                rowCheckboxes().forEach((box) => {
                    box.checked = false;
                });

                if (selectAll) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }

                syncBulkSelection();
            };

            document.getElementById('confirm-bulk-delete')?.addEventListener('click', () => {
                const checked = rowCheckboxes().filter((box) => box.checked);
                const bulkForm = document.getElementById('catamarans-bulk-form');
                const idsContainer = document.getElementById('bulk-delete-ids-container');

                if (checked.length === 0 || ! bulkForm || ! idsContainer) {
                    return;
                }

                idsContainer.innerHTML = '';
                checked.forEach((box) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = box.value;
                    idsContainer.appendChild(input);
                });

                bulkForm.submit();
            });

            resetPageState();

            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.pathname);
            }

            window.addEventListener('pageshow', (event) => {
                if (event.persisted) {
                    resetPageState();
                }
            });

            const photoForm = document.getElementById('add-photo-form');
            const photoSelect = document.getElementById('photo_catamaran_id');
            const photoStoreTemplate = @json(route('pages.catamarans.photos.store', ['catamaran' => '__ID__']));

            const syncPhotoFormAction = () => {
                if (! photoForm || ! photoSelect?.value) return;
                photoForm.action = photoStoreTemplate.replace('__ID__', photoSelect.value);
            };

            photoSelect?.addEventListener('change', syncPhotoFormAction);
            syncPhotoFormAction();

            @if ($errors->any())
                @if ($errors->has('name') || $errors->has('services_provided') || $errors->has('description'))
                    @if (old('_update_url'))
                        @php
                            $editFormAction = old('_update_url');
                        @endphp
                        document.getElementById('edit-catamaran-form').action = @json($editFormAction);
                        document.getElementById('edit_update_url').value = @json($editFormAction);
                        openModal('edit-catamaran-modal');
                    @else
                        openModal('add-catamaran-modal');
                    @endif
                @elseif ($errors->has('photo') || $errors->has('caption') || $errors->has('sort_order') || $errors->has('catamaran_id'))
                    openModal('add-photo-modal');
                @elseif ($errors->has('ids'))
                    openModal('bulk-delete-modal');
                @endif
            @endif
        });
    </script>
@endpush
