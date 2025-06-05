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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->enum('status', ['available', 'in_use', 'maintenance', 'damaged'])->default('available');
            $table->integer('quantity')->default(1);
            $table->integer('quantity_available')->default(1);
            $table->string('serial_number')->nullable();
            $table->text('condition_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
