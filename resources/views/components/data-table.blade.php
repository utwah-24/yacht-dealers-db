@props(['count' => 0, 'title' => null])

<div class="yd-card overflow-hidden p-0">
    <div class="flex items-center justify-between border-b border-yd-line px-5 py-3">
        <div class="flex flex-wrap items-center gap-3">
            <p class="text-sm font-medium text-gray-700">
                @if ($title)
                    <span class="font-mono text-xs text-gray-400">{{ $title }}</span>
                    <span class="mx-2 text-gray-300">·</span>
                @endif
                {{ $count }} record{{ $count === 1 ? '' : 's' }}
            </p>
            @isset($toolbar)
                {{ $toolbar }}
            @endisset
        </div>
        <a href="/api/documentation" target="_blank" class="text-sm font-medium text-yd-green hover:underline">API docs</a>
    </div>

    <div class="overflow-x-auto">
        <table class="yd-table w-full min-w-[640px] text-left text-sm">
            <thead>
                <tr>
                    {{ $head }}
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
    </div>
</div>
