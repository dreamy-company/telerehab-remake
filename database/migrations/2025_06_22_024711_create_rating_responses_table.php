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
        // Ensure 'routine_results' table migration runs before this migration
        Schema::create('rating_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_result_id')->constrained('routine_results');
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->foreignId('therapist_id')->nullable()->constrained('users');

            $table->text('review_doctor')->nullable();
            $table->text('review_therapist')->nullable();
            $table->text('video_doctor')->nullable();
            $table->text('video_therapist')->nullable();
            $table->boolean('isDeleted')->default(false);
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('rating_responses');
        Schema::dropIfExists('rating_respones');
    }
};
