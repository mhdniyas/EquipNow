<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Additional fee columns
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('deposit_amount');
            $table->decimal('setup_fee', 10, 2)->default(0)->after('delivery_fee');
            $table->decimal('insurance_fee', 10, 2)->default(0)->after('setup_fee');
            
            // Service requirement flags
            $table->boolean('delivery_required')->default(false)->after('notes');
            $table->boolean('setup_required')->default(false)->after('delivery_required');
            $table->boolean('insurance_required')->default(false)->after('setup_required');
            
            // Delivery details
            $table->text('delivery_address')->nullable()->after('insurance_required');
            $table->date('delivery_date')->nullable()->after('delivery_address');
            $table->time('delivery_time')->nullable()->after('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_fee',
                'setup_fee',
                'insurance_fee',
                'delivery_required',
                'setup_required',
                'insurance_required',
                'delivery_address',
                'delivery_date',
                'delivery_time'
            ]);
        });
    }
};
