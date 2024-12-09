<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * Campos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = ['fichero_id', 'user_id', 'like'];

    /**
     * Relación: el voto pertenece a un fichero.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fichero()
    {
        return $this->belongsTo(Fichero::class);
    }

    /**
     * Relación: el voto pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
