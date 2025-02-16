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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('age');
            $table->string('job');
            $table->enum('gender',['male','female'])->nullable();
            $table->enum('diabetes',['yes','no'])->nullable();
            $table->enum('kidney_problems',['yes','no'])->nullable();
            $table->enum('pressure',['yes','no'])->nullable();
            $table->enum('heart',['yes','no'])->nullable();
            $table->enum('allergic',['yes','no'])->nullable();
            $table->enum('blood_thinning',['yes','no'])->nullable();
            $table->enum('epidemic_liver',['yes','no'])->nullable();
            $table->enum('thyroid',['yes','no'])->nullable();
            $table->enum('cancer',['yes','no'])->nullable();
            $table->enum('rheumatic',['yes','no'])->nullable();
            $table->text('another_illnesses')->nullable();
            $table->enum('smoked',['yes','no'])->nullable();
            $table->enum('pregnant',['yes','no'])->nullable();
            $table->text('pharmaceutical')->nullable();
            $table->enum('first_visit_to_doctor',['yes','no'])->nullable();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
