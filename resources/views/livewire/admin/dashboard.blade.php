<div class="space-y-8">
    <!-- Statistics Cards -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Content Card -->
            <a href="{{ route('admin.content.index') }}" wire:navigate class="block group">
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-l-blue-500 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2 group-hover:text-blue-600 transition-colors">Total Content</h3>
                            <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-800 transition-colors">{{ $totalContent }}</p>
                            <p class="text-sm text-gray-600 mt-1">Content pieces</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                            <svg class="h-8 w-8 text-blue-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Recent Messages Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-l-green-500 group cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2 group-hover:text-green-600 transition-colors">Recent Messages</h3>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-green-800 transition-colors">{{ $recentMessagesCount }}</p>
                        <p class="text-sm text-gray-600 mt-1">Unread messages</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                        <svg class="h-8 w-8 text-green-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Last Update Card -->
            @if($lastContentUpdate)
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border-l-4 border-l-purple-500 group">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2 group-hover:text-purple-600 transition-colors">Last Update</h3>
                        <p class="text-lg font-semibold text-gray-900 group-hover:text-purple-800 transition-colors">{{ $lastContentUpdate }}</p>
                        <p class="text-sm text-gray-600 mt-1">Content modified</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                        <svg class="h-8 w-8 text-purple-600 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Messages Section -->
    @if($recentMessages->isNotEmpty())
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Recent Contact Messages</h2>
            <flux:button size="sm" variant="ghost" href="#" wire:navigate>
                View all
            </flux:button>
        </div>
        
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="divide-y divide-gray-200">
                @foreach($recentMessages as $message)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar placeholder -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Message content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $message->name }}
                                </h3>
                                <time class="text-sm text-gray-500">
                                    {{ $message->created_at->diffForHumans() }}
                                </time>
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $message->subject ?? 'No subject' }}
                            </p>
                            
                            @if($message->message)
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                {{ Str::limit($message->message, 100) }}
                            </p>
                            @endif
                            
                            <!-- Status indicator -->
                            @if(!$message->read_at)
                            <div class="flex items-center mt-2">
                                <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                <span class="text-xs text-green-600 font-medium">New</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty state for messages -->
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Recent Contact Messages</h2>
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No recent messages</h3>
            <p class="text-gray-500">Contact messages will appear here when received</p>
        </div>
    </div>
    @endif
</div>