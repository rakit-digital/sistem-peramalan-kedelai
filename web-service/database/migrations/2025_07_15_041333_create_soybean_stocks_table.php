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
        Schema::create('soybean_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date')->unique();
            $table->decimal('purchase_kg', 8, 2)->default(0);
            $table->decimal('usage_kg', 8, 2)->default(0);
            $table->decimal('closing_stock_kg', 10, 2);
            $table->text('notes')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soybean_stocks');
    }
};
