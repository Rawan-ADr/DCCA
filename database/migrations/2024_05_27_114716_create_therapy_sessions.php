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
        Schema::create('therapy_sessions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('status',['paid','unpaid']);
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            $table->foreignId('treatment_id')->constrained('treatments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('problem_id')->constrained('problems')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy_sessions');
    }
};
