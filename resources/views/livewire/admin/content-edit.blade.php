<div>
    <!-- Simplified Header -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-zinc-400 mb-3">
            <flux:button size="xs" variant="ghost" href="{{ route('admin.content.index') }}" wire:navigate class="text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('admin.content.title') }}
            </flux:button>
            <svg class="w-4 h-4 text-gray-400 dark:text-zinc-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-700 dark:text-zinc-300 font-medium">
                @if($contentId)
                    {{ __('admin.content.edit') }}
                @else
                    {{ __('admin.content.create') }}
                @endif
            </span>
        </div>

        <!-- Title and Description -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                @if($contentId)
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100 mb-2">{{ __('admin.content.edit') }}</h1>
                    @if($key && $page)
                        <p class="text-gray-600 dark:text-zinc-400">
                            {{ __('admin.content.editing') }} <code class="text-sm bg-gray-100 dark:bg-zinc-800 px-2 py-1 rounded font-mono">{{ $key }}</code>
                            {{ __('admin.content.on') }} <span class="font-medium text-gray-800 dark:text-zinc-300">{{ ucfirst($page) }}</span> {{ __('admin.content.page') }}
                        </p>
                    @endif
                @else
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-zinc-100 mb-2">{{ __('admin.content.create_new') }}</h1>
                    <p class="text-gray-600 dark:text-zinc-400">{{ __('admin.content.create_description') }}</p>
                @endif
            </div>

            <!-- Status Indicators -->
            @if($contentId && $key)
                <div class="flex items-center space-x-2 flex-shrink-0">
                    @if($content)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ __('admin.content.status.has_content') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ __('admin.content.status.empty') }}
                        </span>
                    @endif

                    @if($title)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ __('admin.content.status.has_title') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Column -->
            <div class="space-y-6">
                @if(!$contentId)
                    <!-- Page Selection (for new content) -->
                    <flux:field>
                        <flux:label>{{ __('admin.content.form.page.label') }}</flux:label>
                        <flux:select wire:model.live="page" placeholder="{{ __('admin.content.form.page.placeholder') }}">
                            @if($availablePages)
                                @foreach($availablePages as $pageOption)
                                    <flux:select.option value="{{ $pageOption }}">{{ ucfirst($pageOption) }}</flux:select.option>
                                @endforeach
                            @endif
                        </flux:select>
                        <flux:error name="page" />
                    </flux:field>

                    <!-- Key Selection (for new content) -->
                    @if($page)
                        <flux:field>
                            <flux:label>{{ __('admin.content.form.key.label') }}</flux:label>
                            <flux:select wire:model.live="key" placeholder="{{ __('admin.content.form.key.placeholder') }}">
                                @if($availableKeys && count($availableKeys) > 0)
                                    @foreach($availableKeys as $keyOption)
                                        <flux:select.option value="{{ $keyOption }}">{{ $keyOption }}</flux:select.option>
                                    @endforeach
                                @else
                                    <flux:select.option value="" disabled>{{ __('admin.content.form.key.no_keys') }}</flux:select.option>
                                @endif
                            </flux:select>
                            <flux:error name="key" />
                        </flux:field>
                    @endif
                @else
                    <!-- Display key and page for existing content -->
                    <flux:field>
                        <flux:label>{{ __('admin.content.form.key.label') }}</flux:label>
                        <flux:input value="{{ $key }}" readonly />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('admin.content.form.page.label') }}</flux:label>
                        <flux:input value="{{ ucfirst($page) }}" readonly />
                    </flux:field>
                @endif

                <!-- Title -->
                <flux:field>
                    <flux:label>{{ __('admin.content.form.title.label') }}</flux:label>
                    <flux:input wire:model="title" placeholder="{{ __('admin.content.form.title.placeholder') }}" />
                    <flux:error name="title" />
                </flux:field>

                <!-- Content -->
                <flux:field>
                    <div class="flex items-center justify-between">
                        <flux:label>{{ __('admin.content.form.content.label') }}</flux:label>
                        <div class="text-xs text-gray-500 dark:text-zinc-400">
                            <span wire:ignore>{{ strlen($content) }}</span> {{ __('admin.content.form.content.characters') }}
                        </div>
                    </div>
                    <flux:textarea
                        wire:model.live="content"
                        rows="10"
                        placeholder="{{ __('admin.content.form.content.placeholder') }}"
                        onInput="this.nextElementSibling?.querySelector('span')?.textContent = this.value.length + ' {{ __('admin.content.form.content.characters') }}'"
                    />
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500 dark:text-zinc-400">
                        <div>
                            <span>{{ strlen($content) }}</span> {{ __('admin.content.form.content.characters') }}
                            @if(strlen($content) > 500)
                                <span class="text-amber-600 dark:text-amber-400 ml-2">• {{ __('admin.content.form.content.long') }}</span>
                            @elseif(strlen($content) > 0)
                                <span class="text-green-600 dark:text-green-400 ml-2">• {{ __('admin.content.form.content.good_length') }}</span>
                            @endif
                        </div>
                        @if($content)
                            <div class="text-gray-400 dark:text-zinc-500">
                                ~{{ round(str_word_count($content) / 200, 1) }} {{ __('admin.content.form.content.min_read') }}
                            </div>
                        @endif
                    </div>
                    <flux:error name="content" />
                </flux:field>

                <!-- Simplified Actions Section -->
                <div class="bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-700 pt-6 mt-6">
                    <!-- Primary Action -->
                    <div class="flex justify-between items-center mb-4">
                        <flux:button type="submit" variant="primary" class="px-6">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $contentId ? __('admin.content.form.update_button') : __('admin.content.form.create_button') }}
                        </flux:button>

                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-zinc-400">
                            💡 {{ __('admin.content.form.press') }} <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-100 dark:bg-zinc-800 rounded">Ctrl+S</kbd> {{ __('admin.content.form.to_save') }}
                        </div>
                    </div>

                    <!-- Secondary Actions -->
                    @if($contentId)
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-zinc-800">
                            <div class="flex items-center space-x-3">
                                @if($content)
                                    <flux:button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        onclick="copyToClipboard('{{ addslashes($content) }}', this)"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('admin.content.form.copy_content') }}
                                    </flux:button>
                                @endif

                                <flux:button type="button" wire:click="cancel" variant="ghost" size="sm" class="text-gray-600 dark:text-zinc-400 hover:text-gray-800 dark:hover:text-zinc-300">
                                    {{ __('ui.common.cancel') }}
                                </flux:button>
                            </div>

                            <flux:button
                                type="button"
                                wire:click="delete"
                                variant="danger"
                                size="sm"
                                onclick="return confirm('{{ __('admin.content.form.delete_confirm') }}')"
                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('ui.common.delete') }}
                            </flux:button>
                        </div>
                    @else
                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-zinc-800">
                            <flux:button type="button" wire:click="cancel" variant="ghost" size="sm" class="text-gray-600 dark:text-zinc-400 hover:text-gray-800 dark:hover:text-zinc-300">
                                {{ __('ui.common.cancel') }}
                            </flux:button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview Column -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-zinc-100">{{ __('admin.content.form.preview') }}</h3>

                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 min-h-[300px] shadow-sm">
                    @if($title || $content)
                        @if($title)
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-zinc-100 mb-4">{{ $title }}</h4>
                        @endif

                        @if($content)
                            <div class="prose prose-sm max-w-none text-gray-700 dark:text-zinc-300 leading-relaxed">
                                {!! nl2br(e($content)) !!}
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-center h-full text-center py-12">
                            <div class="space-y-3">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 dark:text-zinc-400">{{ __('admin.content.form.preview_empty') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
