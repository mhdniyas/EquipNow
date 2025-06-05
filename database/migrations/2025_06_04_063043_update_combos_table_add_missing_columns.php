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
        Schema::table('combos', function (Blueprint $table) {
            // Add missing columns
            $table->unsignedBigInteger('category_id')->after('description');
            $table->decimal('combo_price', 10, 2)->after('category_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('combo_price');
            
            // Add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories');
            
            // Remove old columns that aren't needed
            $table->dropColumn(['daily_rate', 'deposit_amount', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            // Remove new columns
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'combo_price', 'status']);
            
            // Add back old columns
            $table->decimal('daily_rate', 10, 2)->after('description');
            $table->decimal('deposit_amount', 10, 2)->after('daily_rate');
            $table->boolean('is_active')->default(true)->after('deposit_amount');
        });
    }
};
