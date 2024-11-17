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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fee_id')->constrained('fees')->cascadeOnDelete();
            $table->float('amount');
            $table->date('date_paid');
            $table->enum('status', ['paid', 'pending', 'failed', '']);
            $table->string('payment_method', 50)->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('deleted_by')->constrained('users')->onDelete('cascade')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
