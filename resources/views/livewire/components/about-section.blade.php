<section id="about" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center">About Us</h2>

        <!-- About Section 1 -->
        <div class="flex flex-col md:flex-row items-center mb-16">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <p class="text-lg text-gray-700">
                    {{ $aboutContent['about_section_1'] ?? 'About section 1' }}
                </p>
            </div>
            <div class="md:w-1/2">
                <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Image 1</span>
                </div>
            </div>
        </div>

        <!-- About Section 2 -->
        <div class="flex flex-col md:flex-row-reverse items-center mb-16">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pl-8">
                <p class="text-lg text-gray-700">
                    {{ $aboutContent['about_section_2'] ?? 'About section 2' }}
                </p>
            </div>
            <div class="md:w-1/2">
                <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Image 2</span>
                </div>
            </div>
        </div>

        <!-- About Section 3 -->
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <p class="text-lg text-gray-700">
                    {{ $aboutContent['about_section_3'] ?? 'About section 3' }}
                </p>
            </div>
            <div class="md:w-1/2">
                <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Image 3</span>
                </div>
            </div>
        </div>
    </div>
</section>
