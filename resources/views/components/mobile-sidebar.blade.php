<!-- Mobile Sidebar -->
<div id="sidebar" class="fixed lg:hidden inset-y-0 left-0 transform -translate-x-full w-64 bg-primary-900 shadow-luxury-lg transition-transform duration-300 ease-in-out z-50">
    <div class="flex flex-col h-full">
        <!-- Logo with close button -->
        <div class="flex items-center justify-between h-20 bg-primary-800 border-b border-primary-700 px-4">
            <h1 class="font-serif text-xl font-bold text-white">
                EquipNow
            </h1>
            <!-- Mobile close button inside sidebar -->
            <button id="sidebar-close-button" 
                    class="p-2 rounded-md text-primary-200 hover:text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-accent-500 transition-colors duration-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- User Info (Mobile) -->
        <div class="bg-primary-800 px-4 py-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-accent-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-primary-300">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
                class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-chart-line mr-4 w-5"></i>
                <span class="font-medium">Dashboard</span>
                @if(request()->routeIs('dashboard'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Equipment -->
            <a href="{{ route('equipment.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('equipment.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-tools mr-4 w-5"></i>
                <span class="font-medium">Equipment</span>
                @if(request()->routeIs('equipment.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Categories -->
            <a href="{{ route('categories.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('categories.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-boxes mr-4 w-5"></i>
                <span class="font-medium">Categories</span>
                @if(request()->routeIs('categories.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Subcategories -->
            <a href="{{ route('subcategories.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('subcategories.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-layer-group mr-4 w-5"></i>
                <span class="font-medium">Subcategories</span>
                @if(request()->routeIs('subcategories.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Combo Packages -->
            <a href="{{ route('combos.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('combos.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-box-open mr-4 w-5"></i>
                <span class="font-medium">Combo Packages</span>
                @if(request()->routeIs('combos.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Bookings -->
            <a href="{{ route('bookings.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('bookings.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-calendar-alt mr-4 w-5"></i>
                <span class="font-medium">Bookings</span>
                @if(request()->routeIs('bookings.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Customers -->
            <a href="{{ route('customers.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('customers.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-users mr-4 w-5"></i>
                <span class="font-medium">Customers</span>
                @if(request()->routeIs('customers.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Payments -->
            <a href="{{ route('payments.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('payments.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-credit-card mr-4 w-5"></i>
                <span class="font-medium">Payments</span>
                @if(request()->routeIs('payments.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Maintenance -->
            <a href="{{ route('maintenance.index') }}" 
                class="mobile-nav-link {{ request()->routeIs('maintenance.*') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-wrench mr-4 w-5"></i>
                <span class="font-medium">Maintenance</span>
                @if(request()->routeIs('maintenance.*'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <div class="border-t border-primary-700 my-4"></div>
            
            <!-- Analytics -->
            <a href="{{ route('admin.analytics') }}" 
                class="mobile-nav-link {{ request()->routeIs('admin.analytics') ? 'bg-accent-600 text-white shadow-lg' : '' }}">
                <i class="fas fa-chart-bar mr-4 w-5"></i>
                <span class="font-medium">Analytics</span>
                @if(request()->routeIs('admin.analytics'))
                    <i class="fas fa-chevron-right ml-auto text-xs"></i>
                @endif
            </a>
            
            <!-- Settings -->
            <a href="#" 
                class="mobile-nav-link">
                <i class="fas fa-cog mr-4 w-5"></i>
                <span class="font-medium">Settings</span>
            </a>
            
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="mobile-nav-link w-full text-left hover:bg-red-600">
                    <i class="fas fa-sign-out-alt mr-4 w-5"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </nav>
    </div>
</div>
