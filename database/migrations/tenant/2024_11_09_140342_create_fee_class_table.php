<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_class', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_id')->index();
            $table->unsignedBigInteger('grade_id')->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_class');
    }
};
