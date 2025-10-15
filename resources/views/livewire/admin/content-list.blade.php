<div>
    <!-- Enhanced Filters Bar -->
    <div class="mb-8">
        <!-- Filters -->
        <div class="flex flex-col lg:flex-row gap-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-zinc-900 dark:to-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700">
            <flux:field class="flex-1">
                <flux:label class="text-gray-700 dark:text-zinc-300 font-medium">Filter by page</flux:label>
                <flux:select wire:model.live="selectedPage" placeholder="All pages">
                    <flux:select.option value="all">All pages</flux:select.option>
                    @foreach($availablePages as $page)
                        <flux:select.option value="{{ $page }}">{{ ucfirst($page) }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field class="flex-1">
                <flux:label class="text-gray-700 dark:text-zinc-300 font-medium">Search content</flux:label>
                <flux:input wire:model.live="search" placeholder="Search by key, title, or content..." />
            </flux:field>

            @if($search || $selectedPage !== 'all')
                <div class="flex items-end">
                    <flux:button
                        size="sm"
                        variant="ghost"
                        wire:click="$set('search', ''); $set('selectedPage', 'all')"
                        class="text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </flux:button>
                </div>
            @endif
        </div>

        <!-- Results Summary -->
        @if($contents->count() > 0)
            <div class="flex items-center justify-between mt-4 px-1">
                <div class="text-sm text-gray-600 dark:text-zinc-400">
                    Showing <span class="font-medium">{{ $contents->count() }}</span> content{{ $contents->count() !== 1 ? 's' : '' }}
                    @if($search)
                        for "<span class="font-medium">{{ $search }}</span>"
                    @endif
                    @if($selectedPage !== 'all')
                        on <span class="font-medium">{{ $selectedPage }}</span> page
                    @endif
                </div>

                @if($contents->count() > 5)
                    <div class="text-xs text-gray-500 dark:text-zinc-500">
                        Use Ctrl+F to search within results
                    </div>
                @endif
            </div>
        @endif
    </div>

    @if($contents->count() > 0)
        {{-- Desktop Table - Hidden on mobile --}}
        <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-zinc-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($contents as $content)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-zinc-800 transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <code class="text-sm font-mono bg-gray-100 dark:bg-zinc-800 group-hover:bg-gray-200 dark:group-hover:bg-zinc-700 px-2 py-1 rounded transition-colors">
                                        {{ $content->key }}
                                    </code>
                                    @if($content->updated_at->gt(now()->subDay()))
                                        <span class="flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 dark:bg-green-500 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if($content->title)
                                        <div class="font-medium text-gray-900 dark:text-zinc-100">{{ $content->title }}</div>
                                        <div class="text-gray-500 dark:text-zinc-400 text-xs">{{ ucfirst(str_replace('_', ' ', $content->key)) }}</div>
                                    @else
                                        <div class="font-medium text-gray-500 dark:text-zinc-400">{{ ucfirst(str_replace('_', ' ', $content->key)) }}</div>
                                        <div class="text-gray-400 dark:text-zinc-500 text-xs">No title set</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ \App\PageBadgeColor::getClasses($content->page) }}">
                                    <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    {{ ucfirst($content->page) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">
                                <div class="max-w-xs">
                                    @if($content->content)
                                        <p class="truncate">{{ Str::limit($content->content, 60) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">{{ strlen($content->content) }} characters</p>
                                    @else
                                        <span class="italic text-gray-400 dark:text-zinc-500">Empty content</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <flux:button
                                        size="xs"
                                        variant="ghost"
                                        :href="route('admin.content.edit', $content->id)"
                                        wire:navigate
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </flux:button>

                                    @if($content->content)
                                        <flux:button
                                            size="xs"
                                            variant="ghost"
                                            x-data="{ copied: false }"
                                            @click="
                                                navigator.clipboard.writeText(@js($content->content));
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            "
                                            class="text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-300"
                                        >
                                            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards - Hidden on desktop --}}
        <div class="md:hidden space-y-4">
            @foreach($contents as $content)
                <x-admin.content-card :content="$content" />
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-zinc-100 mb-2">No content found</h3>
            <p class="text-gray-500 dark:text-zinc-400">Try adjusting your search or filters</p>
        </div>
    @endif

    @if(!empty($missingKeys))
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-zinc-100 mb-4">Missing Content</h3>
            <div class="grid gap-4">
                @foreach($missingKeys as $page => $keys)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                        <h4 class="text-base font-medium text-gray-900 dark:text-zinc-100 mb-3">{{ ucfirst($page) }} page</h4>
                        <div class="space-y-3">
                            @foreach($keys as $key)
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-900 rounded border border-yellow-100 dark:border-yellow-900">
                                    <span class="text-sm font-mono text-gray-700 dark:text-zinc-300">{{ $key }}</span>
                                    <flux:button
                                        size="xs"
                                        variant="primary"
                                        wire:click="createMissingContent('{{ $page }}', '{{ $key }}')"
                                    >
                                        Create
                                    </flux:button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>