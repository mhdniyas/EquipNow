<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MaintenanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, get a reference to the staff user (assumed to be assigned to maintenance)
        $staffUser = User::where('email', 'staff@equipnow.com')->first();
        $adminUser = User::where('email', 'admin@equipnow.com')->first();
        
        if (!$staffUser || !$adminUser) {
            return; // Skip if users don't exist
        }

        // Create some completed maintenance records
        $completedRecords = [
            [
                'equipment_name' => 'JBL PRX815',
                'issue' => 'Speaker emitting buzzing noise when powered on',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(28),
                'cost' => 75.50,
                'resolution_notes' => 'Replaced faulty power supply unit and tested for 48 hours. Issue resolved.',
            ],
            [
                'equipment_name' => 'Shure SM58',
                'issue' => 'Microphone not picking up sound properly',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->subDays(14),
                'cost' => 25.00,
                'resolution_notes' => 'Cleaned internal components and replaced damaged capsule screen. Working normally now.',
            ],
        ];

        // Create some pending/in-progress maintenance records
        $pendingRecords = [
            [
                'equipment_name' => 'Pioneer DDJ-1000',
                'issue' => 'Right channel output not working',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => null,
                'cost' => 0.00, // Set default cost for pending records
                'resolution_notes' => null,
                'status' => MaintenanceRecord::STATUS_IN_PROGRESS,
            ],
            [
                'equipment_name' => 'ADJ Mega Par Profile',
                'issue' => 'One LED not lighting up properly',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => null,
                'cost' => 0.00, // Set default cost for pending records
                'resolution_notes' => null,
                'status' => MaintenanceRecord::STATUS_PENDING,
            ],
        ];

        // Process completed maintenance records
        foreach ($completedRecords as $record) {
            $equipment = Equipment::where('name', $record['equipment_name'])->first();
            
            if ($equipment) {
                MaintenanceRecord::firstOrCreate(
                    [
                        'equipment_id' => $equipment->id,
                        'issue' => $record['issue'],
                        'start_date' => $record['start_date'],
                    ],
                    [
                        'reported_by' => $adminUser->id,
                        'assigned_to' => $staffUser->id,
                        'status' => MaintenanceRecord::STATUS_COMPLETED,
                        'end_date' => $record['end_date'],
                        'cost' => $record['cost'],
                        'resolution_notes' => $record['resolution_notes'],
                    ]
                );
            }
        }

        // Process pending/in-progress maintenance records
        foreach ($pendingRecords as $record) {
            $equipment = Equipment::where('name', $record['equipment_name'])->first();
            
            if ($equipment) {
                // Set equipment to maintenance status
                $equipment->update([
                    'status' => Equipment::STATUS_MAINTENANCE,
                    'quantity_available' => $equipment->quantity_available > 0 ? $equipment->quantity_available - 1 : 0
                ]);
                
                MaintenanceRecord::firstOrCreate(
                    [
                        'equipment_id' => $equipment->id,
                        'issue' => $record['issue'],
                        'start_date' => $record['start_date'],
                    ],
                    [
                        'reported_by' => $adminUser->id,
                        'assigned_to' => $staffUser->id,
                        'status' => $record['status'],
                        'end_date' => $record['end_date'],
                        'cost' => $record['cost'],
                        'resolution_notes' => $record['resolution_notes'],
                    ]
                );
            }
        }
    }
}
