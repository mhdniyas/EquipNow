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
        Schema::create('return_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('return_date');
            $table->text('condition_notes')->nullable();
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->enum('refund_status', ['none', 'partial', 'full'])->default('none');
            $table->text('damages_description')->nullable();
            $table->decimal('damage_charges', 10, 2)->default(0);
            $table->decimal('late_return_charges', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_records');
    }
};
