
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id(); // ID del voto
            $table->foreignId('fichero_id')->constrained()->onDelete('cascade'); // Relación con la tabla ficheros
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla usuarios
            $table->boolean('like')->default(false); // True para like, false para dislike
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}

