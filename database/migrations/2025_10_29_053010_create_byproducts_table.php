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
        Schema::create('byproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('name'); // husks, shells, coir, etc.
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit'); // kg, pieces, bundles, etc.
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->enum('status', ['available', 'sold', 'reserved'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('byproducts');
    }
};
