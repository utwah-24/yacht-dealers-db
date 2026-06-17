@props(['count' => 0])

<div class="yd-card overflow-hidden p-0">
    <div class="flex items-center justify-between border-b border-yd-line px-5 py-3">
        <p class="text-sm font-medium text-gray-700">{{ $count }} record{{ $count === 1 ? '' : 's' }}</p>
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
