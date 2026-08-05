<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('kode_booking')->nullable()->unique(); // Contoh: SRV-2026-0001
            $table->integer('biaya_final')->nullable();           // Diinput manual oleh teknisi nanti
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['kode_booking', 'biaya_final']);
        });
    }
};
