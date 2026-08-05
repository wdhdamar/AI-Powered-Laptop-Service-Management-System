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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('estimasi_sparepart')->nullable(); // Contoh: LCD, Keyboard, Ram
            $table->integer('estimasi_biaya')->nullable();     // Contoh: 500000
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['estimasi_sparepart', 'estimasi_biaya']);
        });
    }
};