<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_session_id')->constrained('movement_sessions')->cascadeOnDelete();
            $table->integer('rep_number');
            $table->float('joint_angle');
            $table->boolean('within_threshold')->default(true);
            $table->json('landmark_snapshot')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_logs');
    }
};
