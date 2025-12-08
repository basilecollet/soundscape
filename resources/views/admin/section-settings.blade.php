@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header with Navigation -->
        <div class="mb-8 pb-6 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-zinc-100">
                        {{ __('admin.settings.section_visibility') }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-zinc-400">
                        {{ __('admin.settings.section_visibility_description') }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        href="{{ route('admin.dashboard') }}"
                        wire:navigate
                        class="inline-flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0" />
                        </svg>
                        {{ __('ui.common.back_to_dashboard') }}
                    </flux:button>
                </div>
            </div>
        </div>

        @livewire('admin.section-settings-manager')
    </div>
@endsection
