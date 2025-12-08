<div class="space-y-6">
    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" icon="exclamation-circle">
            {{ session('error') }}
        </flux:callout>
    @endif

    <div class="grid gap-6">
        @foreach($availableSettings as $page => $pageSettings)
            <div class="bg-white dark:bg-zinc-900 shadow rounded-lg border border-gray-200 dark:border-zinc-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-zinc-100 capitalize">{{ __('admin.settings.sections.' . $page . '.title') }}</h3>
                </div>

                <div class="px-6 py-4 space-y-4">
                    @if(empty($pageSettings))
                        <p class="text-sm text-gray-500 dark:text-zinc-400">{{ __('admin.settings.sections.all_enabled') }}</p>
                    @else
                        @foreach($pageSettings as $section)
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-zinc-100">{{ $section['label'] }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                                        @if($section['section_key'] === 'features')
                                            {{ __('admin.settings.sections.home.features.description') }}
                                        @elseif($section['section_key'] === 'cta')
                                            {{ __('admin.settings.sections.home.cta.description') }}
                                        @elseif($section['section_key'] === 'experience')
                                            {{ __('admin.settings.sections.about.experience.description') }}
                                        @elseif($section['section_key'] === 'services')
                                            {{ __('admin.settings.sections.about.services.description') }}
                                        @elseif($section['section_key'] === 'philosophy')
                                            {{ __('admin.settings.sections.about.philosophy.description') }}
                                        @endif
                                    </p>
                                </div>

                                <flux:switch
                                    wire:click="toggleSection('{{ $section['section_key'] }}', '{{ $page }}')"
                                    :checked="$section['is_enabled']"
                                />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <div class="flex">
            <flux:icon.information-circle class="h-5 w-5 text-blue-400" />
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-400">
                    {{ __('admin.settings.important_notes') }}
                </h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                    <ul class="list-disc space-y-1 ml-5">
                        <li>{{ __('admin.settings.notes.hero_always_enabled') }}</li>
                        <li>{{ __('admin.settings.notes.contact_always_enabled') }}</li>
                        <li>{{ __('admin.settings.notes.immediate_effect') }}</li>
                        <li>{{ __('admin.settings.notes.disabled_not_shown') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
