<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '555-123-4567',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'id_type' => 'Driver\'s License',
                'id_number' => 'DL98765432',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '555-234-5678',
                'address' => '456 Park Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'id_type' => 'Passport',
                'id_number' => 'P123456789',
            ],
            [
                'name' => 'Michael Williams',
                'email' => 'michael.williams@example.com',
                'phone' => '555-345-6789',
                'address' => '789 Broadway',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60601',
                'id_type' => 'Driver\'s License',
                'id_number' => 'DL12345678',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '555-456-7890',
                'address' => '321 Maple Road',
                'city' => 'Miami',
                'state' => 'FL',
                'postal_code' => '33101',
                'id_type' => 'State ID',
                'id_number' => 'ID87654321',
            ],
            [
                'name' => 'Sound & Light Productions',
                'email' => 'bookings@soundlight.com',
                'phone' => '555-789-0123',
                'address' => '987 Industry Way',
                'city' => 'Nashville',
                'state' => 'TN',
                'postal_code' => '37201',
                'id_type' => 'Business License',
                'id_number' => 'BL2023-45678',
            ],
            [
                'name' => 'Event Masters Inc.',
                'email' => 'info@eventmasters.com',
                'phone' => '555-890-1234',
                'address' => '654 Conference Blvd',
                'city' => 'Las Vegas',
                'state' => 'NV',
                'postal_code' => '89101',
                'id_type' => 'Business License',
                'id_number' => 'BL2023-98765',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['email' => $customer['email']],
                $customer
            );
        }
    }
}
