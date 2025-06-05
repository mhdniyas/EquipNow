<section id="home" class="hero-gradient text-white pt-16">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 font-serif">
                Premium Equipment
                <span class="block text-cyan-300">Rental Marketplace</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-blue-100">
                Rent professional-grade equipment for your projects. From construction tools to tech equipment, we have everything you need.
            </p>                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('equipment-page.index') }}" 
                            class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1">
                        Browse Equipment
                    </a>
                    <a href="{{ route('about.index') }}" 
                            class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300">
                        Learn More
                    </a>
                </div>
        </div>
    </div>
</section>
