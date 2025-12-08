@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header with Navigation -->
        <div class="mb-8 pb-6 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-zinc-100">{{ __('admin.dashboard.title') }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-zinc-400">{{ __('admin.dashboard.subtitle') }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        href="{{ route('admin.content.index') }}"
                        wire:navigate
                        class="inline-flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ __('admin.dashboard.quick_actions.manage_content') }}
                    </flux:button>
                </div>
            </div>
        </div>
        
        @livewire('admin.dashboard')
    </div>
@endsection