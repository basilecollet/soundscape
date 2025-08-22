<div>
    <!-- Filters Bar -->
    <div class="mb-8 flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-lg">
        <flux:field class="flex-1">
            <flux:label>Filter by page</flux:label>
            <flux:select wire:model.live="selectedPage" placeholder="All pages">
                <flux:select.option value="all">All pages</flux:select.option>
                @foreach($availablePages as $page)
                    <flux:select.option value="{{ $page }}">{{ ucfirst($page) }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field class="flex-1">
            <flux:label>Search</flux:label>
            <flux:input wire:model.live="search" placeholder="Search contents..." />
        </flux:field>
    </div>

    @if($contents->count() > 0)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contents as $content)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $content->key }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $content->title ?: '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $content->page }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($content->content, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <flux:button 
                                    size="xs" 
                                    variant="ghost" 
                                    :href="route('admin.content.edit', $content->id)" 
                                    wire:navigate
                                >
                                    Edit
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No content found</h3>
            <p class="text-gray-500">Try adjusting your search or filters</p>
        </div>
    @endif

    @if(!empty($missingKeys))
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Missing Content</h3>
            <div class="grid gap-4">
                @foreach($missingKeys as $page => $keys)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h4 class="text-base font-medium text-gray-900 mb-3">{{ ucfirst($page) }} page</h4>
                        <div class="space-y-3">
                            @foreach($keys as $key)
                                <div class="flex items-center justify-between p-3 bg-white rounded border border-yellow-100">
                                    <span class="text-sm font-mono text-gray-700">{{ $key }}</span>
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