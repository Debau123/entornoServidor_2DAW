<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Importar SoftDeletes
use Illuminate\Support\Facades\Storage;

class Fichero extends Model
{
    use SoftDeletes; // Habilitar Soft Deletes

    /**
     * Devuelve el tamaño del archivo si existe, de lo contrario muestra un mensaje alternativo.
     */
    public function size()
    {
        if (Storage::exists($this->path)) {
            return Storage::size($this->path) . ' bytes'; // Mostrar tamaño en bytes
        }
        return 'Archivo no encontrado'; // Mensaje alternativo si el archivo no existe
    }

    /**
     * Relación con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los usuarios con los que se ha compartido el archivo.
     */
    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'shared_files', 'fichero_id', 'user_id')->withTimestamps();
    }
}
