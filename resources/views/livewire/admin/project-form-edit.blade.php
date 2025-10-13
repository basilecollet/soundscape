<div class="max-w-4xl mx-auto">
    <form wire:submit="save">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Project Information</h2>
                <p class="mt-1 text-sm text-gray-600">Update the project details below.</p>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">You can use Markdown formatting for rich text.</p>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <flux:button
                    variant="ghost"
                    href="{{ route('admin.project.index') }}"
                    type="button"
                >
                    Cancel
                </flux:button>

                <flux:button
                    variant="primary"
                    type="submit"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="save">Update Project</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </flux:button>
            </div>
        </div>
    </form>
</div>
