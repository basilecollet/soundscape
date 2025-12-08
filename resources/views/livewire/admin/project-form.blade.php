<div class="max-w-4xl mx-auto">
    <form wire:submit="save">
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-zinc-100">{{ __('admin.projects.form.create_title') }}</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ __('admin.projects.form.form_description') }}</p>
            </div>

            <!-- Form Body -->
            <div class="px-6 py-6 space-y-6">
                <!-- Title (Required) -->
                <div>
                    <flux:label for="title">
                        {{ __('admin.projects.form.title.label') }} <span class="text-red-500">*</span>
                    </flux:label>
                    <flux:input
                        wire:model="title"
                        id="title"
                        placeholder="{{ __('admin.projects.form.title.placeholder') }}"
                        class="mt-1"
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <flux:label for="description">
                        {{ __('admin.projects.form.description.label') }}
                    </flux:label>
                    <flux:textarea
                        wire:model="description"
                        id="description"
                        placeholder="{{ __('admin.projects.form.description.placeholder') }}"
                        rows="6"
                        class="mt-1"
                    />
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">{{ __('admin.projects.form.description.help') }}</p>
                </div>

                <!-- Short Description -->
                <div>
                    <flux:label for="shortDescription">
                        {{ __('admin.projects.form.short_description.label') }}
                    </flux:label>
                    <flux:textarea
                        wire:model="shortDescription"
                        id="shortDescription"
                        placeholder="{{ __('admin.projects.form.short_description.placeholder') }}"
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
                            {{ __('admin.projects.form.client_name.label') }}
                        </flux:label>
                        <flux:input
                            wire:model="clientName"
                            id="clientName"
                            placeholder="{{ __('admin.projects.form.client_name.placeholder') }}"
                            class="mt-1"
                        />
                        @error('clientName')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Date -->
                    <div>
                        <flux:label for="projectDate">
                            {{ __('admin.projects.form.project_date.label') }}
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
            </div>

            <!-- Form Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-zinc-800 border-t border-gray-200 dark:border-zinc-700 flex justify-end space-x-3">
                <flux:button
                    variant="ghost"
                    href="{{ route('admin.project.index') }}"
                    type="button"
                >
                    {{ __('ui.common.cancel') }}
                </flux:button>

                <flux:button
                    variant="primary"
                    type="submit"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove wire:target="save">{{ __('ui.common.create') }}</span>
                    <span wire:loading wire:target="save">{{ __('ui.common.creating') }}</span>
                </flux:button>
            </div>
        </div>
    </form>
</div>