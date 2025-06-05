<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->name }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @can('equipment.view')
                            <a href="{{ route('equipment.index') }}" class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="font-bold text-lg mb-2">Equipment Management</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">View and manage equipment inventory</p>
                            </a>
                        @endcan
                        
                        @role('Admin')
                            <a href="{{ route('admin.users') }}" class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="font-bold text-lg mb-2">User Management</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Manage users and their permissions</p>
                            </a>
                        @endrole
                        
                        @role('Admin|Salesman')
                            <a href="{{ route('bookings.index') }}" class="bg-green-50 dark:bg-green-900 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="font-bold text-lg mb-2">Bookings</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Manage equipment bookings and returns</p>
                            </a>
                        @endrole
                        
                        @role('Admin|Staff')
                            <a href="{{ route('maintenance.index') }}" class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="font-bold text-lg mb-2">Maintenance</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Track equipment maintenance tasks</p>
                            </a>
                        @endrole
                    </div>
                    
                    <div class="mt-8">
                        <h4 class="font-medium text-lg mb-2">Your Role:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(Auth::user()->getRoleNames() as $role)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-sm">{{ $role }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
