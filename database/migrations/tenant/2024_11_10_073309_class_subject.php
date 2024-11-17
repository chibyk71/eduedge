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
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            // * The primary key for the class_subject table.
            $table->unsignedBigInteger('class_id')->index();
            // * The foreign key that references the classes table.
            $table->foreign('class_id')->references('id')->on('grades')->onDelete('cascade');
            // * The primary key for the class_subject table.
            $table->unsignedBigInteger('subject_id')->index();
            // * The foreign key that references the subjects table.
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject');
    }
};
