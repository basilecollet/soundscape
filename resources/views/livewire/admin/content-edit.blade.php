<div>
    <!-- Simplified Header -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-3">
            <flux:button size="xs" variant="ghost" href="{{ route('admin.content.index') }}" wire:navigate class="text-gray-500 hover:text-gray-700">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Content Management
            </flux:button>
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-700 font-medium">
                @if($contentId)
                    Edit Content
                @else
                    Create Content
                @endif
            </span>
        </div>

        <!-- Title and Description -->
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex-1">
                @if($contentId)
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Content</h1>
                    @if($key && $page)
                        <p class="text-gray-600">
                            Editing <code class="text-sm bg-gray-100 px-2 py-1 rounded font-mono">{{ $key }}</code>
                            on <span class="font-medium text-gray-800">{{ ucfirst($page) }}</span> page
                        </p>
                    @endif
                @else
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Create New Content</h1>
                    <p class="text-gray-600">Add new content to your website</p>
                @endif
            </div>

            <!-- Status Indicators -->
            @if($contentId && $key)
                <div class="flex items-center space-x-2 flex-shrink-0">
                    @if($content)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Has Content
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Empty
                        </span>
                    @endif

                    @if($title)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Has Title
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
                        <flux:label>Page</flux:label>
                        <flux:select wire:model.live="page" placeholder="Select a page">
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
                            <flux:label>Content Key</flux:label>
                            <flux:select wire:model.live="key" placeholder="Select a content key">
                                @if($availableKeys && count($availableKeys) > 0)
                                    @foreach($availableKeys as $keyOption)
                                        <flux:select.option value="{{ $keyOption }}">{{ $keyOption }}</flux:select.option>
                                    @endforeach
                                @else
                                    <flux:select.option value="" disabled>No keys available for this page</flux:select.option>
                                @endif
                            </flux:select>
                            <flux:error name="key" />
                        </flux:field>
                    @endif
                @else
                    <!-- Display key and page for existing content -->
                    <flux:field>
                        <flux:label>Content Key</flux:label>
                        <flux:input value="{{ $key }}" readonly />
                    </flux:field>

                    <flux:field>
                        <flux:label>Page</flux:label>
                        <flux:input value="{{ ucfirst($page) }}" readonly />
                    </flux:field>
                @endif

                <!-- Title -->
                <flux:field>
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Optional title" />
                    <flux:error name="title" />
                </flux:field>

                <!-- Content -->
                <flux:field>
                    <div class="flex items-center justify-between">
                        <flux:label>Content</flux:label>
                        <div class="text-xs text-gray-500">
                            <span wire:ignore>{{ strlen($content) }}</span> characters
                        </div>
                    </div>
                    <flux:textarea
                        wire:model.live="content"
                        rows="10"
                        placeholder="Enter your content here..."
                        onInput="this.nextElementSibling?.querySelector('span')?.textContent = this.value.length + ' characters'"
                    />
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                        <div>
                            <span>{{ strlen($content) }}</span> characters
                            @if(strlen($content) > 500)
                                <span class="text-amber-600 ml-2">• Long content</span>
                            @elseif(strlen($content) > 0)
                                <span class="text-green-600 ml-2">• Good length</span>
                            @endif
                        </div>
                        @if($content)
                            <div class="text-gray-400">
                                ~{{ round(str_word_count($content) / 200, 1) }} min read
                            </div>
                        @endif
                    </div>
                    <flux:error name="content" />
                </flux:field>

                <!-- Simplified Actions Section -->
                <div class="bg-white border-t border-gray-200 pt-6 mt-6">
                    <!-- Primary Action -->
                    <div class="flex justify-between items-center mb-4">
                        <flux:button type="submit" variant="primary" class="px-6">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $contentId ? 'Update Content' : 'Create Content' }}
                        </flux:button>

                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            💡 Press <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-100 rounded">Ctrl+S</kbd> to save
                        </div>
                    </div>

                    <!-- Secondary Actions -->
                    @if($contentId)
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-3">
                                @if($content)
                                    <flux:button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        onclick="copyToClipboard('{{ addslashes($content) }}', this)"
                                        class="text-blue-600 hover:text-blue-800"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Copy Content
                                    </flux:button>
                                @endif

                                <flux:button type="button" wire:click="cancel" variant="ghost" size="sm" class="text-gray-600 hover:text-gray-800">
                                    Cancel
                                </flux:button>
                            </div>

                            <flux:button
                                type="button"
                                wire:click="delete"
                                variant="danger"
                                size="sm"
                                onclick="return confirm('⚠️ Are you sure you want to delete this content?\\n\\nThis action cannot be undone.')"
                                class="text-red-600 hover:text-red-800"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </flux:button>
                        </div>
                    @else
                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                            <flux:button type="button" wire:click="cancel" variant="ghost" size="sm" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </flux:button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview Column -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900">Preview</h3>

                <div class="bg-white border border-gray-200 rounded-lg p-6 min-h-[300px] shadow-sm">
                    @if($title || $content)
                        @if($title)
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h4>
                        @endif

                        @if($content)
                            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($content)) !!}
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-center h-full text-center py-12">
                            <div class="space-y-3">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500">Content preview will appear here...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
