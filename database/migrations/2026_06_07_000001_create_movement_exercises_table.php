<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('target_joint');
            $table->json('thresholds'); // {"min_angle":30,"max_angle":160,"target_reps":10}
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_exercises');
    }
};
