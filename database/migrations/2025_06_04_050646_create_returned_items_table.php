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
        Schema::create('returned_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_record_id');
            $table->unsignedBigInteger('equipment_id');
            $table->integer('quantity');
            $table->enum('condition', ['good', 'damaged', 'needs_repair'])->default('good');
            $table->text('damage_description')->nullable();
            $table->decimal('damage_charges', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('return_record_id')->references('id')->on('return_records')->cascadeOnDelete();
            $table->foreign('equipment_id')->references('id')->on('equipment')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returned_items');
    }
};
