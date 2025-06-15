<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions array
        $permissions = [
            // Equipment management permissions
            'equipment.view',
            'equipment.create',
            'equipment.edit',
            'equipment.delete',
            'equipment.maintenance',

            // Category management permissions
            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            // Subcategory management permissions
            'subcategory.view',
            'subcategory.create',
            'subcategory.edit',
            'subcategory.delete',

            // Booking permissions
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'bookings.cancel',
            'bookings.return',

            // Customer permissions
            'customer.view',
            'customer.create',
            'customer.edit',

            // Combo/Bundle permissions
            'combo.view',
            'combo.create',
            'combo.edit',
            'combo.delete',

            // Report permissions
            'report.view',
            'report.export',
            'report.daily',

            // User management permissions
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Define roles and their permissions
        $roles = [
            'Admin' => $permissions,
            'Salesman' => [
                'equipment.view',
                'category.view',
                'subcategory.view',
                'bookings.view',
                'bookings.create',
                'bookings.edit',
                'bookings.return',
                'customer.view',
                'customer.create',
                'customer.edit',
                'combo.view',
                'report.daily',
                'report.view'
            ],
            'Staff' => [
                'equipment.view',
                'equipment.maintenance',
                'bookings.view'
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($rolePermissions);
        }

        // Create default users if they don't exist
        if (!User::where('email', 'admin@equipnow.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@equipnow.com',
                'password' => bcrypt('password')
            ]);
            $admin->assignRole('Admin');
        }

        if (!User::where('email', 'salesman@equipnow.com')->exists()) {
            $salesman = User::factory()->create([
                'name' => 'Salesman User',
                'email' => 'salesman@equipnow.com',
                'password' => bcrypt('password')
            ]);
            $salesman->assignRole('Salesman');
        }

        if (!User::where('email', 'staff@equipnow.com')->exists()) {
            $staff = User::factory()->create([
                'name' => 'Staff User',
                'email' => 'staff@equipnow.com',
                'password' => bcrypt('password')
            ]);
            $staff->assignRole('Staff');
        }
    }
}
