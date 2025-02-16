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
        Schema::create('doctor_days', function (Blueprint $table) {
            $table->id();
            $table->time('begin_consultation_time')->nullable();
            $table->time('end_consultation_time')->nullable();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            $table->foreignId('day_id')->constrained('days')->cascadeOnDelete()->cascadeOnUpdate()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_days');
    }
};
