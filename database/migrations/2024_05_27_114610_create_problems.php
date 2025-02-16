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
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tooth_name');
            $table->decimal('expected_cost',8,2);
            $table->integer('Expected_number_of_sessions');
            $table->enum('damege_status',['weak','moderate','severe']);
            $table->enum('status',['undertreatment','done','ToDo']);
            $table->foreignId('checklist_id')->constrained('checklists')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
