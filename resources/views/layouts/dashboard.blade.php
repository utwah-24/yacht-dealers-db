<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Yacht Dealers</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="h-full overflow-hidden">
    @php
        $navItems = [
            'operations' => [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6h16M4 12h16M4 18h10'],
                ['route' => 'pages.catamarans', 'label' => 'Catamarans', 'icon' => 'M3 15c3-6 6-9 9-9s6 3 9 9'],
                ['route' => 'pages.bookings', 'label' => 'Bookings', 'icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9l-4-4H9z'],
                ['route' => 'pages.guests', 'label' => 'Guests', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H7m8 0H7'],
            ],
            'charters' => [
                ['route' => 'pages.routes', 'label' => 'Routes', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 15.382V6.618a1 1 0 011.553-.894L9 2l6 3 6-3v13l-6 3-6-3z'],
                ['route' => 'pages.summaries', 'label' => 'Summaries', 'icon' => 'M9 12h6m-6 4h6M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H9L5 8v10a2 2 0 002 2z'],
            ],
        ];
        $iconRail = [
            ['route' => 'dashboard', 'icon' => 'M4 6h16M4 12h16M4 18h10'],
            ['route' => 'pages.catamarans', 'icon' => 'M4 8l8-4 8 4v8l-8 4-8-4V8z'],
            ['route' => 'pages.bookings', 'icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9l-4-4H9z'],
            ['route' => 'pages.guests', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H7m8 0H7'],
        ];
    @endphp

    <div class="flex h-full flex-col md:flex-row">
        {{-- Mobile top bar --}}
        <div class="flex items-center justify-between border-b border-yd-line bg-white px-4 py-3 md:hidden">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yd-green text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M3 15c3-6 6-9 9-9s6 3 9 9M3 15h18"/></svg>
                </div>
                <span class="text-sm font-semibold text-yd-green">Yacht Dealers</span>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600">Dashboard</a>
        </div>

        <div class="flex min-h-0 flex-1">
        {{-- Icon rail --}}
        <aside class="hidden w-[72px] shrink-0 flex-col items-center border-r border-yd-line bg-white py-4 xl:flex">
            <div class="mb-6 flex h-10 w-10 items-center justify-center rounded-full bg-yd-green text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M3 15c3-6 6-9 9-9s6 3 9 9M3 15h18"/>
                </svg>
            </div>
            <nav class="flex flex-col items-center gap-1">
                @foreach ($iconRail as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex h-10 w-10 items-center justify-center rounded-xl transition {{ request()->routeIs($item['route']) ? 'bg-yd-green-soft text-yd-green' : 'text-gray-400 hover:bg-gray-50' }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                        </svg>
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Sidebar --}}
        <aside class="hidden w-[248px] shrink-0 flex-col border-r border-yd-line bg-white md:flex">
            <div class="flex items-center justify-between px-5 py-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-yd-green text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" d="M3 15c3-6 6-9 9-9s6 3 9 9M3 15h18"/>
                        </svg>
                    </div>
                    <span class="text-[15px] font-semibold text-yd-green">Yacht Dealers</span>
                </div>
                <button type="button" class="rounded-lg p-1 text-gray-400 hover:bg-gray-50" aria-label="Collapse sidebar">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-5 overflow-y-auto px-3 pb-4">
                <div>
                    <p class="yd-section-title">Operations</p>
                    <div class="space-y-0.5">
                        @foreach ($navItems['operations'] as $item)
                            <a href="{{ route($item['route']) }}" class="yd-nav-link {{ request()->routeIs($item['route']) ? 'yd-nav-link-active' : '' }}">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="yd-section-title">Charters</p>
                    <div class="space-y-0.5">
                        @foreach ($navItems['charters'] as $item)
                            <a href="{{ route($item['route']) }}" class="yd-nav-link {{ request()->routeIs($item['route']) ? 'yd-nav-link-active' : '' }}">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="yd-section-title">System</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('pages.settings') }}" class="yd-nav-link {{ request()->routeIs('pages.settings') ? 'yd-nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                        <div class="yd-nav-link justify-between">
                            <span class="flex items-center gap-3">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0-1.414 1.414M7.05 16.95l-1.414 1.414"/><circle cx="12" cy="12" r="4"/></svg>
                                Dark mode
                            </span>
                            <span class="relative inline-flex h-5 w-9 shrink-0 rounded-full bg-gray-200">
                                <span class="absolute top-0.5 left-0.5 h-4 w-4 rounded-full bg-white shadow"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="border-t border-yd-line px-4 py-4">
                <div class="flex items-center gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=YachtDealer" alt="Admin" class="h-10 w-10 rounded-full bg-gray-100">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-gray-900">Admin Manager</p>
                        <p class="truncate text-xs text-gray-500">Fleet Manager</p>
                    </div>
                </div>
                <a href="#" class="mt-3 block text-sm font-medium text-gray-500 hover:text-yd-green">Log out</a>
            </div>
        </aside>

        {{-- Main --}}
        <main class="min-w-0 flex-1 overflow-y-auto bg-yd-page">
            @yield('content')
        </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
