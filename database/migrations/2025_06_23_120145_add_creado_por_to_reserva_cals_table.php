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
        Schema::table('reserva_cals', function (Blueprint $table) {
            $table->string('creado_por')->nullable(); // O usa unsignedBigInteger si es user_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserva_cals', function (Blueprint $table) {
            $table->dropColumn('creado_por');
        });
    }
};
