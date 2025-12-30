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
        Schema::create('rehabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehabilitation_type_id')->constrained('rehab_types');
            $table->string('name');
            $table->text('description');
            $table->string('video_url')->nullable();
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
        Schema::dropIfExists('rehabs');
    }
};
