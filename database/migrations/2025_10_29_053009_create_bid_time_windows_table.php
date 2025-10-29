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
        Schema::create('bid_time_windows', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "1 Hour", "1 Day", etc.
            $table->integer('duration_minutes');
            $table->timestamps();
        });

        // Seed default time windows
        DB::table('bid_time_windows')->insert([
            ['name' => '1 Hour', 'duration_minutes' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '6 Hours', 'duration_minutes' => 360, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '1 Day', 'duration_minutes' => 1440, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '3 Days', 'duration_minutes' => 4320, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '1 Week', 'duration_minutes' => 10080, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_time_windows');
    }
};
