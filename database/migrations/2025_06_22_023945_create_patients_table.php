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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('medical_record_number')->unique()->nullable();
            $table->string('bpjs_number')->unique()->nullable();
            $table->string('bpjs_card')->nullable();
            $table->string('prosthetic')->nullable();
            $table->date('prosthetic_since')->nullable();
            $table->text('address')->nullable();
            $table->boolean('isDeleted')->default(false);
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
