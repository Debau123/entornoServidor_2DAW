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
        Schema::table('ficheroes', function (Blueprint $table) {
            $table->boolean('privado')->default(false); // Añadir columna 'privado' para indicar si el archivo es privado o no
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ficheroes', function (Blueprint $table) {
            $table->dropColumn('privado'); // Eliminar la columna 'privado' si se deshace la migración
        });
    }
};
