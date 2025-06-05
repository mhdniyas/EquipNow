<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-tools text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">EquipNow</span>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Your trusted partner for premium equipment rentals. Professional-grade tools and equipment for all your project needs.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-white transition-colors">
                        <i class="fab fa-linkedin text-xl"></i>
                    </a>
                </div>
            </div>                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white transition-colors">Categories</a></li>
                        <li><a href="{{ route('equipment-page.index') }}" class="text-gray-300 hover:text-white transition-colors">Equipment</a></li>
                        <li><a href="{{ route('about.index') }}" class="text-gray-300 hover:text-white transition-colors">About</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact</h3>
                <ul class="space-y-2 text-gray-300">
                    <li class="flex items-center">
                        <i class="fas fa-phone mr-2"></i>
                        <span>+91 98765 43210</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>info@equipnow.com</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>Mumbai, Maharashtra</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="border-gray-700 my-8">
        
        <div class="text-center text-gray-300">
            <p>&copy; {{ date('Y') }} EquipNow. All rights reserved. Built with ❤️ for equipment rental professionals.</p>
        </div>
    </div>
</footer>
