<nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
     x-data="{ scrolled: false, mobileMenuOpen: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="{ 'bg-transparent': !scrolled, 'bg-white shadow-md': scrolled }">
    <div class="container mx-auto px-6 lg:px-12 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-xl font-bold transition-colors" 
               :class="{ 'text-white': !scrolled, 'text-gray-800': scrolled }">
                {{ config('app.name') }}
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('home') }}" 
                   class="transition-colors {{ request()->routeIs('home') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">
                    Home
                </a>
                <a href="{{ route('about') }}" 
                   class="transition-colors {{ request()->routeIs('about') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">
                    About
                </a>
                <a href="{{ route('contact') }}" 
                   class="transition-colors {{ request()->routeIs('contact') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">
                    Contact
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden flex flex-col space-y-1 focus:outline-none"
                :class="{ 'text-white': !scrolled, 'text-gray-800': scrolled }"
            >
                <span class="w-6 h-0.5 bg-current transition-all duration-300" 
                      :class="{ 'rotate-45 translate-y-1.5': mobileMenuOpen }"></span>
                <span class="w-6 h-0.5 bg-current transition-all duration-300" 
                      :class="{ 'opacity-0': mobileMenuOpen }"></span>
                <span class="w-6 h-0.5 bg-current transition-all duration-300" 
                      :class="{ '-rotate-45 -translate-y-1.5': mobileMenuOpen }"></span>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="md:hidden mt-4 pb-4 border-t"
             :class="{ 'border-white/20': !scrolled, 'border-gray-200': scrolled }"
             style="display: none;">
            <div class="flex flex-col space-y-3 pt-4">
                <a href="{{ route('home') }}" 
                   class="transition-colors {{ request()->routeIs('home') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }"
                   @click="mobileMenuOpen = false">
                    Home
                </a>
                <a href="{{ route('about') }}" 
                   class="transition-colors {{ request()->routeIs('about') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }"
                   @click="mobileMenuOpen = false">
                    About
                </a>
                <a href="{{ route('contact') }}" 
                   class="transition-colors {{ request()->routeIs('contact') ? 'font-semibold' : '' }}" 
                   :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }"
                   @click="mobileMenuOpen = false">
                    Contact
                </a>
            </div>
        </div>
    </div>
</nav>
