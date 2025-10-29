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
        Schema::create('harvest_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('harvest_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->json('category_bids'); // [{category_id: 1, quantity: 100, price_per_unit: 10.50, min_bid: 10.00}]
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'won', 'lost', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_bids');
    }
};
