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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // Name of the subject (e.g., "Mathematics")
            $table->string('code')->unique()->nullable();  // Optional unique code for the subject
            $table->string('description')->nullable();     // Brief description of the subject
            $table->string('status')->default('active');   // Status (e.g., active, inactive)
            $table->timestamps();

            // Add any additional columns or constraints as needed
            $table->softDeletes(); // Optional soft deletes for archiving subjects

            // Add any additional columns or constraints as needed
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
