@php use Illuminate\Support\Carbon; @endphp
@props([
    'project' => null
])

<div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
    <!-- Header: Title + Status Badge -->
    <div class="flex justify-between items-start mb-2">
        <h3 class="font-semibold text-gray-900 text-sm flex-1 pr-2 line-clamp-2">
            {{ $project->title }}
        </h3>

        <!-- Status Badge -->
        @if($project->status === 'draft')
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shrink-0">
                Draft
            </span>
        @elseif($project->status === 'published')
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 shrink-0">
                Published
            </span>
        @elseif($project->status === 'archived')
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 shrink-0">
                Archived
            </span>
        @endif
    </div>

    <!-- Slug -->
    <p class="text-xs text-gray-500 mb-3 font-mono">
        {{ $project->slug }}
    </p>

    <!-- Metadata: Client & Date -->
    @if($project->clientName || $project->projectDate)
        <div class="space-y-2 mb-3 pb-3 border-b border-gray-100">
            <!-- Client -->
            @if($project->clientName)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="truncate">{{ $project->clientName }}</span>
                </div>
            @endif

            <!-- Date -->
            @if($project->projectDate)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ Carbon::parse($project->projectDate)->format('d M Y') }}</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Actions -->
    <div class="flex gap-2">
        <flux:button
                size="sm"
                variant="ghost"
                href="{{ route('admin.project.edit', ['project' => $project->slug]) }}"
                class="flex-1"
        >
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </flux:button>
    </div>
</div>
