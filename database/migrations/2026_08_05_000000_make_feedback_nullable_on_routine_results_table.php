<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom `feedback` dan `date` sebelumnya NOT NULL tanpa default, sehingga
     * submit routine_result dari API (yang mengirim feedback opsional dan tidak
     * mengirim date) gagal di MySQL strict mode.
     */
    public function up(): void
    {
        Schema::table('routine_results', function (Blueprint $table) {
            $table->text('feedback')->nullable()->change();
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routine_results', function (Blueprint $table) {
            $table->string('feedback')->nullable(false)->change();
            $table->date('date')->nullable(false)->change();
        });
    }
};
