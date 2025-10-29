<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('harvest_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // husked, unhusked, scraped
            $table->string('unit'); // per_nut, per_kg
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed default categories
        DB::table('harvest_categories')->insert([
            ['name' => 'husked', 'unit' => 'per_nut', 'description' => 'Husked coconuts (per nut)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'unhusked', 'unit' => 'per_nut', 'description' => 'Unhusked coconuts (per nut)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'scraped', 'unit' => 'per_kg', 'description' => 'Scraped coconut (per kg)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harvest_categories');
    }
};
