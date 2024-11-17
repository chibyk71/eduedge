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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id')->index();   // Refers to a transport route
            $table->unsignedBigInteger('student_id')->index(); // Refers to the student's user/profile ID
            $table->foreignId('pickup_location')->constrained('addresses')->nullable();     // Optional pickup location for the student
            $table->foreignId('dropoff_location')->constrained('addresses')->nullable();    // Optional dropoff location for the student
            $table->string('status')->default('active');       // Tracks status (e.g., active, inactive)
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
