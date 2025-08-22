<div>
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
                    <flux:label>Content</flux:label>
                    <flux:textarea wire:model="content" rows="10" placeholder="Enter your content here..." />
                    <flux:error name="content" />
                </flux:field>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3">
                    <flux:button type="submit" variant="primary" size="sm">
                        {{ $contentId ? 'Update Content' : 'Create Content' }}
                    </flux:button>
                    
                    <flux:button type="button" wire:click="cancel" variant="ghost" size="sm">
                        Cancel
                    </flux:button>
                    
                    @if($contentId)
                        <flux:button 
                            type="button" 
                            wire:click="delete" 
                            variant="danger" 
                            size="sm"
                            onclick="return confirm('Are you sure you want to delete this content?')"
                        >
                            Delete
                        </flux:button>
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