<section id="home" class="min-h-screen flex items-center bg-gradient-to-b from-[#F2EFE6] to-[#A4B465] pt-16">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-gray-800">Soundscape Audio</h1>
            <p class="text-lg text-gray-700">
                {{ $homeContent ?? 'Welcome to Soundscape Audio' }}
            </p>
        </div>

        <div class="md:w-1/2 flex justify-center">
            <svg class="w-full max-w-md" width="300" height="300" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Globe lines -->
                <circle cx="150" cy="150" r="120" stroke="black" stroke-width="1" fill="none" />

                <!-- Horizontal lines -->
                <ellipse cx="150" cy="150" rx="120" ry="30" stroke="black" stroke-width="1" fill="none" />
                <ellipse cx="150" cy="150" rx="120" ry="60" stroke="black" stroke-width="1" fill="none" />
                <ellipse cx="150" cy="150" rx="120" ry="90" stroke="black" stroke-width="1" fill="none" />

                <!-- Vertical lines -->
                <path d="M150 30 C 150 30, 270 150, 150 270" stroke="black" stroke-width="1" fill="none" />
                <path d="M150 30 C 150 30, 210 150, 150 270" stroke="black" stroke-width="1" fill="none" />
                <path d="M150 30 C 150 30, 90 150, 150 270" stroke="black" stroke-width="1" fill="none" />
                <path d="M150 30 C 150 30, 30 150, 150 270" stroke="black" stroke-width="1" fill="none" />
                <path d="M30 150 L 270 150" stroke="black" stroke-width="1" />
            </svg>
        </div>
    </div>
</section>
