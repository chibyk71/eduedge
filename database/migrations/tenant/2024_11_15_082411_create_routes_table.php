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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicle_number');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->time('departure_time')->nullable();       // Departure time from the starting point
            $table->time('arrival_time')->nullable();         // Expected arrival time at the school
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
