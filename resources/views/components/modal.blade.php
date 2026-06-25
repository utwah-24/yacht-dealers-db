@props([
    'id',
    'title',
])

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/40 p-4"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    <div class="yd-card w-full max-w-lg shadow-xl">
        <div class="mb-5 flex items-start justify-between gap-4">
            <h2 id="{{ $id }}-title" class="text-base font-semibold text-gray-900">{{ $title }}</h2>
            <button
                type="button"
                class="rounded-lg p-1 text-gray-400 hover:bg-gray-50"
                data-modal-close="{{ $id }}"
                aria-label="Close"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{ $slot }}
    </div>
</div>
