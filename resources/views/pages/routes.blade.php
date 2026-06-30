@extends('layouts.dashboard')

@section('title', 'Routes')

@php
    $oldDestinations = old('destinations');
    $oldDestinationsText = is_array($oldDestinations) ? implode("\n", $oldDestinations) : $oldDestinations;
@endphp

@section('content')
    <x-page-header title="Routes" description="Departure points and destinations for each catamaran.">
        <x-slot:actions>
            <button type="button" class="yd-btn-primary" data-modal-open="add-route-modal" {{ $catamarans->isEmpty() ? 'disabled' : '' }}>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                </svg>
                Add route
            </button>
        </x-slot:actions>

        @if (session('success'))
            <div class="yd-alert-success" id="success-alert">{{ session('success') }}</div>
        @endif

        <x-data-table :count="$routes->count()" title="routes">
            <x-slot:head>
                <th>ID</th>
                <th>Catamaran ID</th>
                <th>Catamaran</th>
                <th>Departure</th>
                <th>Destinations</th>
                <th>Created</th>
                <th>Updated</th>
                <th class="w-24 text-right">Actions</th>
            </x-slot:head>
            <x-slot:body>
                @forelse ($routes as $route)
                    <tr>
                        <td>{{ $route->id }}</td>
                        <td>{{ $route->catamaran_id }}</td>
                        <td class="font-medium text-gray-900">{{ $route->catamaran?->name ?? '-' }}</td>
                        <td>{{ $route->departure }}</td>
                        <td class="max-w-[320px] whitespace-normal">{{ is_array($route->destinations) ? implode(', ', $route->destinations) : $route->destinations }}</td>
                        <td class="whitespace-nowrap text-gray-500">{{ $route->created_at?->format('M j, Y g:i A') }}</td>
                        <td class="whitespace-nowrap text-gray-500">{{ $route->updated_at?->format('M j, Y g:i A') }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-0.5">
                                <button
                                    type="button"
                                    class="yd-icon-btn edit-route-btn"
                                    title="Edit route {{ $route->id }}"
                                    data-route-id="{{ $route->id }}"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    class="yd-icon-btn yd-icon-btn-danger"
                                    title="Delete route {{ $route->id }}"
                                    data-modal-open="delete-route-modal"
                                    data-route-name="{{ $route->catamaran?->name ? $route->catamaran->name.' - '.$route->departure : $route->departure }}"
                                    data-delete-url="{{ route('pages.routes.destroy', $route) }}"
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
                        <td colspan="8" class="py-10 text-center text-gray-500">
                            No routes yet. Add a route to connect a catamaran with its departure point and destinations.
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-data-table>
    </x-page-header>

    <x-modal id="add-route-modal" title="Add route">
        <form method="POST" action="{{ route('pages.routes.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="catamaran_id" class="yd-label">Catamaran</label>
                <select
                    id="catamaran_id"
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
                <label for="departure" class="yd-label">Departure</label>
                <input
                    id="departure"
                    name="departure"
                    type="text"
                    value="{{ old('departure') }}"
                    class="yd-input @error('departure') border-red-300 ring-red-100 @enderror"
                    placeholder="Dar es Salaam Marina"
                    required
                >
                @error('departure')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="destinations" class="yd-label">Destinations</label>
                <textarea
                    id="destinations"
                    name="destinations"
                    rows="4"
                    class="yd-input @error('destinations') border-red-300 ring-red-100 @enderror"
                    placeholder="Bongoyo Island&#10;Mbudya Island"
                    required
                >{{ $oldDestinationsText }}</textarea>
                <p class="mt-1.5 text-xs text-gray-400">Enter one destination per line, or separate them with commas.</p>
                @error('destinations')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="add-route-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary">Save route</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="edit-route-modal" title="Edit route">
        <form method="POST" action="{{ old('_update_url', '#') }}" id="edit-route-form" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="_update_url" id="edit_update_url" value="{{ old('_update_url') }}">

            <div>
                <label for="edit_catamaran_id" class="yd-label">Catamaran</label>
                <select
                    id="edit_catamaran_id"
                    name="catamaran_id"
                    class="yd-input @error('catamaran_id') border-red-300 ring-red-100 @enderror"
                    required
                >
                    <option value="">Select catamaran</option>
                    @foreach ($catamarans as $catamaran)
                        <option value="{{ $catamaran->id }}">{{ $catamaran->name }}</option>
                    @endforeach
                </select>
                @error('catamaran_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="edit_departure" class="yd-label">Departure</label>
                <input
                    id="edit_departure"
                    name="departure"
                    type="text"
                    value="{{ old('departure') }}"
                    class="yd-input @error('departure') border-red-300 ring-red-100 @enderror"
                    required
                >
                @error('departure')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="edit_destinations" class="yd-label">Destinations</label>
                <textarea
                    id="edit_destinations"
                    name="destinations"
                    rows="4"
                    class="yd-input @error('destinations') border-red-300 ring-red-100 @enderror"
                    required
                >{{ $oldDestinationsText }}</textarea>
                <p class="mt-1.5 text-xs text-gray-400">Enter one destination per line, or separate them with commas.</p>
                @error('destinations')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" class="yd-btn-secondary" data-modal-close="edit-route-modal">Cancel</button>
                <button type="submit" class="yd-btn-primary">Save changes</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="delete-route-modal" title="Delete route">
        <p class="text-sm text-gray-600">
            Are you sure you want to delete <span id="delete-route-name" class="font-semibold text-gray-900"></span>?
        </p>
        <form method="POST" action="" id="delete-route-form" class="mt-6 flex justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button" class="yd-btn-secondary" data-modal-close="delete-route-modal">Cancel</button>
            <button type="submit" class="yd-btn-danger">Delete</button>
        </form>
    </x-modal>

    <script type="application/json" id="routes-edit-data">
        {!! json_encode($routes->mapWithKeys(fn ($route) => [
            $route->id => [
                'catamaran_id' => $route->catamaran_id,
                'departure' => $route->departure,
                'destinations' => is_array($route->destinations) ? implode("\n", $route->destinations) : $route->destinations,
                'update_url' => route('pages.routes.update', $route),
            ],
        ]), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const routesEditData = (() => {
                const source = document.getElementById('routes-edit-data');
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

            document.querySelectorAll('[data-modal-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modalId = button.dataset.modalOpen;

                    if (modalId === 'delete-route-modal') {
                        document.getElementById('delete-route-name').textContent = button.dataset.routeName;
                        document.getElementById('delete-route-form').action = button.dataset.deleteUrl;
                    }

                    openModal(modalId);
                });
            });

            document.querySelectorAll('.edit-route-btn').forEach((button) => {
                button.addEventListener('click', () => {
                    const route = routesEditData[button.dataset.routeId];
                    if (! route) return;

                    const form = document.getElementById('edit-route-form');
                    form.action = route.update_url;
                    document.getElementById('edit_update_url').value = route.update_url;
                    document.getElementById('edit_catamaran_id').value = route.catamaran_id;
                    document.getElementById('edit_departure').value = route.departure;
                    document.getElementById('edit_destinations').value = route.destinations;

                    openModal('edit-route-modal');
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

            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.pathname);
            }

            @if ($errors->any())
                @if ($errors->has('catamaran_id') || $errors->has('departure') || $errors->has('destinations') || $errors->has('destinations.*'))
                    @if (old('_update_url'))
                        @php
                            $editFormAction = old('_update_url');
                        @endphp
                        document.getElementById('edit-route-form').action = @json($editFormAction);
                        document.getElementById('edit_update_url').value = @json($editFormAction);
                        document.getElementById('edit_catamaran_id').value = @json(old('catamaran_id'));
                        document.getElementById('edit_departure').value = @json(old('departure'));
                        document.getElementById('edit_destinations').value = @json($oldDestinationsText);
                        openModal('edit-route-modal');
                    @else
                        openModal('add-route-modal');
                    @endif
                @endif
            @endif
        });
    </script>
@endpush
