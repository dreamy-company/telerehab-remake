<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movement_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_session_id')->constrained('movement_sessions')->cascadeOnDelete();
            $table->foreignId('flagged_by')->constrained('users')->cascadeOnDelete();
            $table->string('flag_type'); // incomplete_range | wrong_form | missed_reps
            $table->text('note')->nullable();
            $table->boolean('reviewed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movement_flags');
    }
};
