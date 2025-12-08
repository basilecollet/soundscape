@php use Illuminate\Support\Carbon; @endphp
<div class="max-w-7xl mx-auto">
    @if($projects->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-700">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-zinc-100">{{ __('admin.projects.empty_state.title') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">{{ __('admin.projects.empty_state.description') }}</p>
            <div class="mt-6">
                <flux:button href="{{ route('admin.project.create') }}" variant="primary">
                    {{ __('admin.projects.empty_state.create_first') }}
                </flux:button>
            </div>
        </div>
    @else
        {{-- Desktop Table - Hidden on mobile --}}
        <div
            class="hidden md:block bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('ui.common.project') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('ui.common.status') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('ui.common.client') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('ui.common.date') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-zinc-400 uppercase tracking-wider">
                            {{ __('ui.common.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @if($project->featuredImage)
                                        <img
                                            src="{{ $project->featuredImage->thumbUrl }}"
                                            alt="{{ $project->featuredImage->alt ?? $project->title }}"
                                            class="w-16 h-12 object-cover rounded border border-gray-200 dark:border-zinc-700 flex-shrink-0"
                                        >
                                    @else
                                        <div
                                            class="w-16 h-12 bg-gray-100 dark:bg-zinc-800 rounded border border-gray-200 dark:border-zinc-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-zinc-500" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-zinc-100">
                                            {{ $project->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-zinc-400">
                                            {{ $project->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->status === 'draft')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            {{ __('ui.status.draft') }}
                                        </span>
                                @elseif($project->status === 'published')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('ui.status.published') }}
                                        </span>
                                @elseif($project->status === 'archived')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            {{ __('ui.status.archived') }}
                                        </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-zinc-100">
                                {{ $project->clientName ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-zinc-400">
                                @if($project->projectDate)
                                    {{ Carbon::parse($project->projectDate)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    href="{{ route('admin.project.edit', ['project' => $project->slug]) }}"
                                >
                                    {{ __('ui.common.edit') }}
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards - Hidden on desktop --}}
        <div class="md:hidden space-y-4">
            @foreach($projects as $project)
                <x-admin.project-card :project="$project"/>
            @endforeach
        </div>
    @endif
</div>
