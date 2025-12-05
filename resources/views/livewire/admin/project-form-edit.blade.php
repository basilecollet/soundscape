<div class="max-w-4xl mx-auto">
    <!-- Flash Messages -->
    <x-flash-message />

    <form wire:submit="save">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-zinc-100">Project Information</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">Update the project details below.</p>
                </div>

                <!-- Status Badge - Desktop (top right) -->
                <div class="ml-4 mt-0.5 hidden md:block flex-shrink-0">
                    <x-admin.status-badge :status="$project->status" />
                </div>
            </div>

            <!-- Status Badge - Mobile (below title) -->
            <div class="md:hidden px-6 pt-4 pb-2">
                <x-admin.status-badge :status="$project->status" />
            </div>

            <!-- Form Body -->
            <div class="px-6 py-6 space-y-6">
                <!-- Title (Required) -->
                <div>
                    <flux:label for="title">
                        Title <span class="text-red-500">*</span>
                    </flux:label>
                    <flux:input
                        wire:model="title"
                        id="title"
                        placeholder="Enter project title"
                        class="mt-1"
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <flux:label for="description">
                        Description
                    </flux:label>
                    <flux:textarea
                        wire:model="description"
                        id="description"
                        placeholder="Enter project description (supports Markdown)"
                        rows="6"
                        class="mt-1"
                    />
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">You can use Markdown formatting for rich text.</p>
                </div>

                <!-- Short Description -->
                <div>
                    <flux:label for="shortDescription">
                        Short Description
                    </flux:label>
                    <flux:textarea
                        wire:model="shortDescription"
                        id="shortDescription"
                        placeholder="Enter a brief description (max 500 characters)"
                        rows="3"
                        class="mt-1"
                    />
                    @error('shortDescription')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Two column layout for Client & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Client Name -->
                    <div>
                        <flux:label for="clientName">
                            Client Name
                        </flux:label>
                        <flux:input
                            wire:model="clientName"
                            id="clientName"
                            placeholder="Enter client name"
                            class="mt-1"
                        />
                        @error('clientName')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Date -->
                    <div>
                        <flux:label for="projectDate">
                            Project Date
                        </flux:label>
                        <flux:input
                            wire:model="projectDate"
                            id="projectDate"
                            type="date"
                            class="mt-1"
                        />
                        @error('projectDate')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bandcamp Player -->
                <div>
                    <flux:label for="bandcampPlayer">
                        Bandcamp Player
                    </flux:label>
                    <flux:textarea
                        wire:model="bandcampPlayer"
                        id="bandcampPlayer"
                        placeholder="Paste your Bandcamp embed iframe code here"
                        rows="4"
                        class="mt-1 font-mono text-sm"
                    />
                    @error('bandcampPlayer')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">
                        Copy the entire iframe embed code from Bandcamp (starting with &lt;iframe...)
                    </p>
                </div>

                <!-- Media Management Section -->
                <div class="border-t border-gray-200 dark:border-zinc-700 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-zinc-100 mb-4">Project Images</h3>

                    <!-- Featured Image -->
                    <div class="mb-6">
                        <flux:label>Featured Image</flux:label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400 mb-3">
                            The main image that will be displayed for this project<br>
                            <span class="font-medium">Requirements:</span> Min. 800x600px, max 10MB, formats: JPEG, PNG, GIF, WebP
                        </p>

                        @if($project->getFirstMedia('featured'))
                            <!-- Display Current Featured Image -->
                            <div class="relative inline-block">
                                <img
                                    src="{{ $project->getFirstMediaUrl('featured') }}"
                                    alt="Featured image"
                                    class="w-64 h-48 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                />
                                <button
                                    type="button"
                                    wire:click="deleteFeaturedImage"
                                    wire:confirm="Are you sure you want to delete this image?"
                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-2 transition-colors"
                                    aria-label="Delete featured image"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <!-- Upload Featured Image -->
                            <div>
                                <input
                                    type="file"
                                    wire:model="featuredImage"
                                    accept="image/*"
                                    id="featuredImage"
                                    class="hidden"
                                />
                                <label
                                    for="featuredImage"
                                    class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-lg cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-colors"
                                >
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">Click to upload featured image</p>
                                    </div>
                                </label>

                                @if ($featuredImage)
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-600 dark:text-zinc-400 mb-2">Preview:</p>
                                        <img
                                            src="{{ $this->getFeaturedImagePreviewUrl() }}"
                                            class="w-64 h-48 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                        >
                                        <flux:button
                                            type="button"
                                            wire:click="saveFeaturedImage"
                                            variant="primary"
                                            size="sm"
                                            class="mt-2"
                                        >
                                            Upload Featured Image
                                        </flux:button>
                                    </div>
                                @endif

                                @error('featuredImage')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <flux:label>Gallery Images</flux:label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400 mb-3">
                            Additional images for this project gallery (max 10 images)<br>
                            <span class="font-medium">Requirements:</span> Min. 800x600px, max 10MB each, formats: JPEG, PNG, GIF, WebP
                        </p>

                        <!-- Display Current Gallery Images -->
                        @if($project->getMedia('gallery')->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                                @foreach($project->getMedia('gallery') as $media)
                                    <div class="relative group">
                                        <img
                                            src="{{ $media->getUrl() }}"
                                            alt="Gallery image"
                                            class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-zinc-700"
                                        />
                                        <button
                                            type="button"
                                            wire:click="deleteGalleryImage({{ $media->id }})"
                                            wire:confirm="Are you sure you want to delete this image?"
                                            class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity"
                                            aria-label="Delete gallery image"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Upload Gallery Images -->
                        <div>
                            <input
                                type="file"
                                wire:model="galleryImages"
                                accept="image/*"
                                multiple
                                id="galleryImages"
                                class="hidden"
                            />
                            <label
                                for="galleryImages"
                                class="flex items-center justify-center w-full px-4 py-6 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-lg cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-colors"
                            >
                                <div class="text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-zinc-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">Click to upload gallery images (multiple allowed)</p>
                                </div>
                            </label>

                            @if (count($galleryImages) > 0)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 dark:text-zinc-400 mb-2">Preview ({{ count($galleryImages) }} image{{ count($galleryImages) > 1 ? 's' : '' }}):</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($galleryImages as $image)
                                            <img src="{{ $this->getGalleryImagePreviewUrl($image) }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">
                                        @endforeach
                                    </div>
                                    <flux:button
                                        type="button"
                                        wire:click="saveGalleryImages"
                                        variant="primary"
                                        size="sm"
                                        class="mt-2"
                                    >
                                        Upload Gallery Images
                                    </flux:button>
                                </div>
                            @endif

                            @error('galleryImages.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700">
                <!-- Save/Cancel Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mb-6 pb-6 border-b border-gray-200 dark:border-zinc-700">
                    <flux:button
                        variant="filled"
                        href="{{ route('admin.project.index') }}"
                        type="button"
                        class="w-full sm:w-auto"
                    >
                        Cancel
                    </flux:button>

                    <flux:button
                        variant="primary"
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto"
                    >
                        <span wire:loading.remove wire:target="save">Update Project</span>
                        <span wire:loading wire:target="save">Updating...</span>
                    </flux:button>
                </div>

                <!-- Status Management Section -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-zinc-100 mb-2">Project Status</h3>
                    <p class="text-xs text-gray-600 dark:text-zinc-400 mb-4">
                        Change the publication status of this project.
                        @if($project->status->isDraft())
                            Publishing requires a description to be set.
                        @endif
                    </p>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                        @if($this->canPublish)
                            <flux:modal.trigger name="confirm-publish">
                                <flux:button
                                    variant="primary"
                                    type="button"
                                    wire:loading.attr="disabled"
                                    wire:target="publish"
                                    class="w-full sm:w-auto"
                                >
                                    <span wire:loading.remove wire:target="publish">Publish Project</span>
                                    <span wire:loading wire:target="publish">Publishing...</span>
                                </flux:button>
                            </flux:modal.trigger>
                        @endif

                        @if($this->canArchive)
                            <flux:modal.trigger name="confirm-archive">
                                <flux:button variant="danger" type="button" class="w-full sm:w-auto">
                                    Archive Project
                                </flux:button>
                            </flux:modal.trigger>
                        @endif

                        @if($this->canDraft)
                            <flux:modal.trigger name="confirm-draft">
                                <flux:button variant="filled" type="button" class="w-full sm:w-auto">
                                    Set to Draft
                                </flux:button>
                            </flux:modal.trigger>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Publish Confirmation Modal -->
    <x-admin.confirmation-modal
        name="confirm-publish"
        title="Publish this project?"
        message="This project will become visible on your public portfolio. Make sure all content is finalized and a description is provided."
        action="publish"
        actionText="Publish Project"
        actionVariant="primary"
    >
        @if(empty($description))
            <strong class="block mt-2 text-red-600 dark:text-red-400">
                ⚠️ Warning: No description is currently set. Publishing will fail without a description.
            </strong>
        @endif
    </x-admin.confirmation-modal>

    <!-- Archive Confirmation Modal -->
    @if($this->canArchive)
        <x-admin.confirmation-modal
            name="confirm-archive"
            title="Archive this project?"
            message="This project will no longer be visible on your public portfolio. You can restore it later by setting it back to draft or published status."
            action="archive"
            actionText="Archive Project"
            actionVariant="danger"
        />
    @endif

    <!-- Draft Confirmation Modal -->
    @if($this->canDraft)
        <x-admin.confirmation-modal
            name="confirm-draft"
            title="Set back to draft?"
            action="draft"
            actionText="Set to Draft"
            actionVariant="primary"
        >
            This project will be set back to draft status and will no longer be publicly visible
            @if($project->status->isPublished())
                on your portfolio.
            @else
                if it was archived.
            @endif
            You can publish it again at any time.
        </x-admin.confirmation-modal>
    @endif
</div>
