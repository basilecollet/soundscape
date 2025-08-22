<div>
    <!-- Header with Breadcrumb and Status -->
    <div class="mb-8 pb-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <flux:button size="sm" variant="ghost" href="{{ route('admin.content.index') }}" wire:navigate>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Content
                </flux:button>
                
                <div class="text-gray-400">/</div>
                
                <div>
                    @if($contentId)
                        <h1 class="text-2xl font-bold text-gray-900">Edit Content</h1>
                        <p class="text-gray-600">
                            @if($key)
                                Editing <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $key }}</code>
                                @if($page) 
                                    on <span class="font-medium">{{ ucfirst($page) }}</span> page
                                @endif
                            @else
                                Loading content...
                            @endif
                        </p>
                    @else
                        <h1 class="text-2xl font-bold text-gray-900">Create New Content</h1>
                        <p class="text-gray-600">Add new content to your website</p>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center space-x-3">
                @if($contentId && $key)
                    <!-- Content Status Indicators -->
                    <div class="flex items-center space-x-2 text-sm">
                        @if($content)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Has Content
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Empty
                            </span>
                        @endif
                        
                        @if($title)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Has Title
                            </span>
                        @endif
                    </div>
                @endif
            </div>
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
                            @foreach($availablePages as $pageOption)
                                <flux:select.option value="{{ $pageOption }}">{{ ucfirst($pageOption) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="page" />
                    </flux:field>

                    <!-- Key Selection (for new content) -->
                    <flux:field>
                        <flux:label>Content Key</flux:label>
                        <flux:select wire:model="key" placeholder="Select a content key">
                            @foreach($availableKeys as $keyOption)
                                <flux:select.option value="{{ $keyOption }}">{{ $keyOption }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="key" />
                    </flux:field>
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

                <!-- Enhanced Actions -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Primary Actions -->
                        <div class="flex gap-3 flex-1">
                            <flux:button type="submit" variant="primary" size="sm" class="flex-1 sm:flex-initial">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $contentId ? 'Update Content' : 'Create Content' }}
                            </flux:button>
                            
                            <flux:button type="button" wire:click="cancel" variant="ghost" size="sm">
                                Cancel
                            </flux:button>
                        </div>
                        
                        <!-- Secondary Actions -->
                        <div class="flex gap-2">
                            @if($contentId && $content)
                                <flux:button 
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    onclick="navigator.clipboard.writeText('{{ addslashes($content) }}'); 
                                             const btn = this; 
                                             const original = btn.innerHTML; 
                                             btn.innerHTML = '✓ Copied!'; 
                                             setTimeout(() => btn.innerHTML = original, 2000);"
                                    class="text-gray-600 hover:text-gray-800"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Copy
                                </flux:button>
                            @endif
                            
                            @if($contentId)
                                <flux:button 
                                    type="button" 
                                    wire:click="delete" 
                                    variant="danger" 
                                    size="sm"
                                    onclick="return confirm('⚠️ Are you sure you want to delete this content?\\n\\nThis action cannot be undone.')"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </flux:button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Save Shortcuts Hint -->
                    <div class="mt-3 text-xs text-gray-500 border-t pt-3">
                        💡 <strong>Tips:</strong> Use <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-200 rounded">Ctrl+S</kbd> to save quickly
                        @if($content)
                            | <kbd class="px-1.5 py-0.5 text-xs font-mono bg-gray-200 rounded">Ctrl+C</kbd> to copy content
                        @endif
                    </div>
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