<div>
    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form Column -->
            <div class="space-y-4">
                @if(!$contentId)
                    <!-- Page Selection (for new content) -->
                    <div>
                        <label for="page" class="block text-sm font-medium mb-2">Page</label>
                        <select wire:model.live="page" id="page" class="form-select w-full">
                            @foreach($availablePages as $pageOption)
                                <option value="{{ $pageOption }}">{{ ucfirst($pageOption) }}</option>
                            @endforeach
                        </select>
                        @error('page') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Key Selection (for new content) -->
                    <div>
                        <label for="key" class="block text-sm font-medium mb-2">Content Key</label>
                        <select wire:model="key" id="key" class="form-select w-full">
                            <option value="">Select a key</option>
                            @foreach($availableKeys as $keyOption)
                                <option value="{{ $keyOption }}">{{ $keyOption }}</option>
                            @endforeach
                        </select>
                        @error('key') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @else
                    <!-- Display key and page for existing content -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Content Key</label>
                        <p class="text-gray-700 bg-gray-100 p-2 rounded">{{ $key }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Page</label>
                        <p class="text-gray-700 bg-gray-100 p-2 rounded">{{ ucfirst($page) }}</p>
                    </div>
                @endif

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium mb-2">Title</label>
                    <input wire:model="title" type="text" id="title" class="form-input w-full" placeholder="Optional title">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium mb-2">Content</label>
                    <textarea wire:model="content" id="content" rows="10" class="form-textarea w-full" placeholder="Enter your content here..."></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        {{ $contentId ? 'Update' : 'Create' }}
                    </button>
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        Cancel
                    </button>
                    @if($contentId)
                        <button type="button" wire:click="delete" onclick="return confirm('Are you sure you want to delete this content?')" class="btn btn-danger">
                            Delete
                        </button>
                    @endif
                </div>
            </div>

            <!-- Preview Column -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Preview</h3>
                <div class="border border-gray-200 rounded-lg p-4 min-h-[300px] bg-gray-50">
                    @if($title)
                        <h4 class="font-bold text-lg mb-3">{{ $title }}</h4>
                    @endif
                    @if($content)
                        <div class="prose">
                            {!! nl2br(e($content)) !!}
                        </div>
                    @else
                        <p class="text-gray-500 italic">Content preview will appear here...</p>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>