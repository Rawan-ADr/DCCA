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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->integer('num_sessions');
            $table->date('first_sessions')->nullable();
            $table->date('last_sessions')->nullable();
            $table->integer('doctor_id');
            $table->integer('num_doctors');
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
