@php
$config = [
    'success' => [
        'container' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
        'icon' => 'text-green-600 dark:text-green-400',
        'text' => 'text-green-800 dark:text-green-300',
        'button' => 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300',
        'iconPath' => 'M5 13l4 4L19 7',
        'label' => 'Dismiss success message',
    ],
    'error' => [
        'container' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        'icon' => 'text-red-600 dark:text-red-400',
        'text' => 'text-red-800 dark:text-red-300',
        'button' => 'text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300',
        'iconPath' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'label' => 'Dismiss error message',
    ],
];

// Detect which type of message exists in session
$type = null;
if (session()->has('success')) {
    $type = 'success';
} elseif (session()->has('error')) {
    $type = 'error';
}

$styles = $type ? $config[$type] : null;
@endphp

@if ($type && $styles)
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 {{ $styles['container'] }} border rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 {{ $styles['icon'] }} mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $styles['iconPath'] }}" />
                </svg>
                <p class="text-sm font-medium {{ $styles['text'] }}">{{ session($type) }}</p>
            </div>
            <button @click="show = false" type="button" class="ml-4 {{ $styles['button'] }} transition-colors" aria-label="{{ $styles['label'] }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
@endif
