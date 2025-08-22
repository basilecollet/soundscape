<x-layouts.app :title="$title ?? 'Administration'">
    {{ $slot ?? '' }}
    @yield('content')
</x-layouts.app>