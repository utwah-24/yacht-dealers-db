@props([
    'title',
    'description' => null,
])

<div class="px-5 py-6 lg:px-8 lg:py-7">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-[22px] font-semibold text-gray-900">{{ $title }}</h1>
            @if ($description)
                <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
            @endif
        </div>
        {{ $actions ?? '' }}
    </div>

    {{ $slot }}
</div>
