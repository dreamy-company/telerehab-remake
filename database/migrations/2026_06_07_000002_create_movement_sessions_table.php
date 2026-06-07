<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('movement_exercise_id')->constrained('movement_exercises')->cascadeOnDelete();
            $table->foreignId('rehab_routine_id')->nullable()->constrained('rehab_routines')->nullOnDelete();
            $table->date('session_date');
            $table->integer('total_reps')->default(0);
            $table->float('avg_angle')->nullable();
            $table->float('max_angle')->nullable();
            $table->enum('status', ['in_progress', 'completed', 'flagged'])->default('in_progress');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_sessions');
    }
};
