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
        Schema::table('registro_reservas', function (Blueprint $table) {
            DB::statement("ALTER TABLE registro_reservas MODIFY analista ENUM(
				'Por Asignar',
				'Anabel Santana',
				'Eva Ortega',
				'Helvetia Bernal',
				'Liseth Rodriguez',
				'Melanie Taylor',
				'Veronica de Ureña',
				'Walter Lizondro',
				'Yesenia Delgado'
			)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registro_reservas', function (Blueprint $table) {
            //
        });
    }
};
