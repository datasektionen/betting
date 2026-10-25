<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('sms')->whereNull('day')->update(['day' => 1]);

        Schema::table('sms', function (Blueprint $table) {
            $table->integer('day')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms', function (Blueprint $table) {
            $table->integer('day')->nullable()->change();
        });
    }
};
