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
    Schema::table('ficheroes', function (Blueprint $table) { // Cambiado a 'ficheroes'
        $table->unsignedBigInteger('descargas')->default(0);
    });
}

public function down()
{
    Schema::table('ficheroes', function (Blueprint $table) { // Cambiado a 'ficheroes'
        $table->dropColumn('descargas');
    });
}

    
};
