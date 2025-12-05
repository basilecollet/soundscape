@props([
    'name',
    'title',
    'message' => '',
    'action',
    'actionText',
    'actionVariant' => 'primary',
])

<flux:modal name="{{ $name }}" class="max-w-lg">
    <form wire:submit="{{ $action }}" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $title }}</flux:heading>
            <flux:subheading>
                {{ $message }}
                {{ $slot }}
            </flux:subheading>
        </div>

        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
                <flux:button variant="ghost" type="button">Cancel</flux:button>
            </flux:modal.close>
            <flux:button
                variant="{{ $actionVariant }}"
                type="submit"
                wire:loading.attr="disabled"
                wire:target="{{ $action }}"
            >
                <span wire:loading.remove wire:target="{{ $action }}">{{ $actionText }}</span>
                <span wire:loading wire:target="{{ $action }}">{{ $actionText }}ing...</span>
            </flux:button>
        </div>
    </form>
</flux:modal>