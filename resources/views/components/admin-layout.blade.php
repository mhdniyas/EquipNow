@props(['title' => null, 'subtitle' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'EquipNow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js for analytics -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-primary-50 overflow-x-hidden">
        <div class="min-h-screen flex max-w-full">
            <!-- Include Mobile Sidebar Component -->
            <x-mobile-sidebar />
            
            <!-- Mobile menu overlay -->
            <div id="mobile-menu-overlay" 
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300 ease-in-out"></div>
            
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:block lg:static inset-y-0 left-0 w-64 bg-primary-900 shadow-luxury-lg transition-transform duration-300 ease-in-out z-50 lg:z-auto">
                <div class="flex flex-col h-full">
                    <!-- Logo with close button -->
                    <div class="flex items-center justify-between h-20 bg-primary-800 border-b border-primary-700 px-4">
                        <h1 class="font-serif text-xl lg:text-2xl font-bold text-white">
                            EquipNow Admin
                        </h1>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-chart-line mr-3"></i>
                            Dashboard
                        </a>
                        
                        <a href="{{ route('equipment.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('equipment.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-tools mr-3"></i>
                            Equipment
                        </a>
                        
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('categories.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-boxes mr-3"></i>
                            Categories
                        </a>
                        
                        <a href="{{ route('subcategories.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('subcategories.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-layer-group mr-3"></i>
                            Subcategories
                        </a>
                        
                        <a href="{{ route('combos.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('combos.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-box-open mr-3"></i>
                            Combo Packages
                        </a>
                        
                        <a href="{{ route('bookings.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('bookings.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            Bookings
                        </a>
                        
                        <a href="{{ route('customers.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('customers.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-users mr-3"></i>
                            Customers
                        </a>
                        
                        <a href="{{ route('payments.index') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('payments.*') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-credit-card mr-3"></i>
                            Payments
                        </a>
                        
                        <a href="#" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200">
                            <i class="fas fa-wrench mr-3"></i>
                            Maintenance
                        </a>

                        <div class="border-t border-primary-700 my-4"></div>

                        <a href="{{ route('admin.analytics') }}" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-accent-600 text-white' : '' }}">
                            <i class="fas fa-chart-bar mr-3"></i>
                            Analytics
                        </a>

                        <a href="#" 
                           class="flex items-center px-4 py-3 text-primary-200 rounded-lg hover:bg-primary-800 hover:text-white transition-colors duration-200">
                            <i class="fas fa-cog mr-3"></i>
                            Settings
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center w-full px-4 py-3 text-primary-200 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 lg:ml-0">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b border-primary-100">
                    <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                        <div class="flex items-center">
                            <!-- Mobile menu button -->
                            <button id="mobile-menu-button" 
                                    class="lg:hidden mr-4 p-2 rounded-md text-primary-600 hover:text-primary-900 hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-accent-500 transition-colors duration-200">
                                <!-- Hamburger icon -->
                                <svg id="menu-icon" class="h-6 w-6 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <!-- Close icon (hidden by default) -->
                                <svg id="close-icon" class="h-6 w-6 hidden transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <div class="flex-1 min-w-0">
                                <h2 class="font-serif text-xl lg:text-2xl font-semibold text-primary-900 truncate">
                                    {{ $title ?? 'Dashboard' }}
                                </h2>
                                @if(isset($subtitle))
                                    <p class="text-primary-600 mt-1 text-sm lg:text-base hidden sm:block">{{ $subtitle }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 lg:space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-primary-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-primary-500">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
                            </div>
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-accent-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm lg:text-base"></i>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-6 overflow-x-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        <!-- Mobile Navigation JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobile-menu-overlay');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                const sidebarCloseButton = document.getElementById('sidebar-close-button');
                
                let isMenuOpen = false;
                
                function openMobileMenu() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
                    
                    // Switch icons
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    
                    // Add active state to button
                    mobileMenuButton.classList.add('bg-primary-100', 'text-primary-900');
                    
                    isMenuOpen = true;
                }
                
                function closeMobileMenu() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    
                    // Switch icons back
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    
                    // Remove active state from button
                    mobileMenuButton.classList.remove('bg-primary-100', 'text-primary-900');
                    
                    isMenuOpen = false;
                }
                
                function toggleMobileMenu() {
                    if (isMenuOpen) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                }
                
                // Event listeners
                mobileMenuButton.addEventListener('click', toggleMobileMenu);
                overlay.addEventListener('click', closeMobileMenu);
                sidebarCloseButton.addEventListener('click', closeMobileMenu);
                
                // Close menu when clicking on navigation links (mobile only)
                const navLinks = sidebar.querySelectorAll('nav a, nav button[type="submit"]');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 1024 && isMenuOpen) {
                            setTimeout(closeMobileMenu, 150); // Small delay for better UX
                        }
                    });
                });
                
                // Close menu on window resize if screen becomes large
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024 && isMenuOpen) {
                        closeMobileMenu();
                    }
                });
                
                // Keyboard support - ESC key to close menu
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && isMenuOpen) {
                        closeMobileMenu();
                    }
                });
                
                // Prevent scrolling issues on mobile
                sidebar.addEventListener('touchmove', function(e) {
                    e.stopPropagation();
                });
                
                // Swipe gesture support for mobile
                let startX = 0;
                let currentX = 0;
                let isDragging = false;
                
                sidebar.addEventListener('touchstart', function(e) {
                    startX = e.touches[0].clientX;
                    isDragging = true;
                });
                
                sidebar.addEventListener('touchmove', function(e) {
                    if (!isDragging) return;
                    currentX = e.touches[0].clientX;
                    const diffX = startX - currentX;
                    
                    // If swiping left and moved more than 50px, close menu
                    if (diffX > 50 && isMenuOpen) {
                        closeMobileMenu();
                        isDragging = false;
                    }
                });
                
                sidebar.addEventListener('touchend', function() {
                    isDragging = false;
                });
            });
        </script>
    </body>
</html>
