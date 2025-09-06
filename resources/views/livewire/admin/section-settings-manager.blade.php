<div class="space-y-6">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Section Settings</h1>
            <p class="mt-2 text-sm text-gray-700">Manage which sections are visible on your website pages. Hero sections and contact page sections cannot be disabled.</p>
        </div>
    </div>

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
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 capitalize">{{ $page }} Page</h3>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    @if(empty($pageSettings))
                        <p class="text-sm text-gray-500">All sections on this page are always enabled and cannot be disabled.</p>
                    @else
                        @foreach($pageSettings as $section)
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $section['label'] }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($section['section_key'] === 'features')
                                            Controls the display of feature highlights on the home page
                                        @elseif($section['section_key'] === 'cta')
                                            Controls the call-to-action section at the bottom of the home page
                                        @elseif($section['section_key'] === 'experience')
                                            Controls the statistics section showing years of experience, projects, and clients
                                        @elseif($section['section_key'] === 'services')
                                            Controls the services list section
                                        @elseif($section['section_key'] === 'philosophy')
                                            Controls the philosophy section with company values
                                        @endif
                                    </p>
                                </div>
                                
                                <flux:switch 
                                    wire:click="toggleSection('{{ $section['section_key'] }}', '{{ $page }}')"
                                    {{ $section['is_enabled'] ? 'checked' : '' }}
                                />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <flux:icon.information-circle class="h-5 w-5 text-blue-400" />
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">
                    Important Notes
                </h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc space-y-1 ml-5">
                        <li>Hero sections are always displayed and cannot be disabled</li>
                        <li>All contact page sections are always enabled</li>
                        <li>Changes take effect immediately on the frontend</li>
                        <li>Disabled sections will not appear in the website navigation or content</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
