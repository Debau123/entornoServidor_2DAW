<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Solo agrega la columna si no existe
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                      ->after('id')
                      ->default(2) // ID por defecto, rol 'user'
                      ->constrained('roles')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}
