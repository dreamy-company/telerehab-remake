<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->after('role');
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
        });

        // Migrate existing string country values to country_id
        DB::table('users')->whereNotNull('country')->orderBy('id')->each(function ($user) {
            $country = DB::table('countries')
                ->whereRaw('LOWER(name) = ?', [strtolower($user->country)])
                ->first();
            if ($country) {
                DB::table('users')->where('id', $user->id)->update(['country_id' => $country->id]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->default('')->after('role');
        });

        // Restore string values from country_id
        DB::table('users')->whereNotNull('country_id')->orderBy('id')->each(function ($user) {
            $country = DB::table('countries')->where('id', $user->country_id)->first();
            if ($country) {
                DB::table('users')->where('id', $user->id)->update(['country' => $country->name]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
