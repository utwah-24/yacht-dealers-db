@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
    <x-page-header title="Settings" description="System configuration and API access.">
        <div class="grid gap-5 lg:grid-cols-2">
            <div class="yd-card">
                <h2 class="text-base font-semibold text-gray-900">API</h2>
                <p class="mt-2 text-sm text-gray-500">Manage data via the REST API and Swagger documentation.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ url('/api/documentation') }}" target="_blank" class="inline-flex items-center rounded-lg bg-yd-green px-4 py-2 text-sm font-medium text-white hover:opacity-90">
                        Open Swagger docs
                    </a>
                    <a href="{{ url('/api/v1/catamarans') }}" target="_blank" class="inline-flex items-center rounded-lg border border-yd-line bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        API base URL
                    </a>
                </div>
            </div>

            <div class="yd-card">
                <h2 class="text-base font-semibold text-gray-900">Database</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4 border-b border-yd-line pb-3">
                        <dt class="text-gray-500">Connection</dt>
                        <dd class="font-medium text-gray-900">{{ config('database.default') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-yd-line pb-3">
                        <dt class="text-gray-500">Database</dt>
                        <dd class="font-medium text-gray-900">{{ config('database.connections.'.config('database.default').'.database') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500">App environment</dt>
                        <dd class="font-medium text-gray-900">{{ config('app.env') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </x-page-header>
@endsection
