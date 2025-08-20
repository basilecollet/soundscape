<div>
    <div class="mb-6 flex gap-4">
        <select wire:model.live="selectedPage" class="form-select">
            <option value="all">All pages</option>
            @foreach($availablePages as $page)
                <option value="{{ $page }}">{{ ucfirst($page) }}</option>
            @endforeach
        </select>

        <input wire:model.live="search" placeholder="Search contents..." class="form-input" />
    </div>

    @if($contents->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Title</th>
                    <th>Page</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contents as $content)
                    <tr>
                        <td>{{ $content->key }}</td>
                        <td>{{ $content->title }}</td>
                        <td>
                            <span class="badge">{{ $content->page }}</span>
                        </td>
                        <td>
                            {{ Str::limit($content->content, 50) }}
                        </td>
                        <td>
                            <a href="{{ route('admin.content.edit', $content->id) }}" class="btn btn-sm" wire:navigate>
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500">No content found.</p>
        </div>
    @endif

    @if(!empty($missingKeys))
        <div class="mt-8">
            <h3>Missing Content</h3>
            <div class="mt-4 space-y-4">
                @foreach($missingKeys as $page => $keys)
                    <div class="card">
                        <h4>{{ ucfirst($page) }} page</h4>
                        <div class="mt-2 space-y-2">
                            @foreach($keys as $key)
                                <div class="flex items-center justify-between">
                                    <span>{{ $key }}</span>
                                    <button wire:click="createMissingContent('{{ $page }}', '{{ $key }}')" class="btn btn-sm">
                                        Create
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>