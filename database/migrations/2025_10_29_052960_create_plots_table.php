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
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('size', 10, 2); // in acres
            $table->string('location');
            $table->integer('tree_count')->nullable();
            $table->integer('potential_harvest')->nullable(); // estimated nuts per harvest
            $table->enum('harvest_frequency', ['weekly', 'biweekly', 'monthly', 'custom'])->default('monthly');
            $table->string('custom_frequency')->nullable();
            $table->boolean('is_harvest_sold')->default(true);
            $table->boolean('can_deliver')->default(false);
            $table->json('available_categories')->nullable(); // ['husked', 'unhusked', 'scraped']
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
