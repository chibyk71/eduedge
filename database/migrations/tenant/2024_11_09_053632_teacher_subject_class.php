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
        Schema::create('teacher_subject_class', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->index(); // Foreign key to teachers table
            $table->unsignedBigInteger('subject_id')->index(); // Foreign key to subjects table
            $table->unsignedBigInteger('section_id')->index();   // Foreign key to classes table
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_class');
    }
};
