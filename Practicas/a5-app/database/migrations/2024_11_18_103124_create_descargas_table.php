<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('descargas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fichero_id');
            $table->date('fecha'); // Almacenar el día de la descarga
            $table->timestamps();
    
            // Relación con la tabla ficheroes
            $table->foreign('fichero_id')->references('id')->on('ficheroes')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('descargas');
    }
    
};
