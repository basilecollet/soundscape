@props(['status'])

@php
$colorClasses = match(true) {
    $status->isDraft() => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    $status->isPublished() => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
    $status->isArchived() => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
    default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClasses}"]) }}>
    {{ __("ui.status.{$status->value}") }}
</span>
