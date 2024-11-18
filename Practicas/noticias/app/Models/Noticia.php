<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'cuerpo', 'enlace'];

    // Relación con el modelo User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Voto
    public function votos(): HasMany
    {
        return $this->hasMany(Voto::class);
    }

 
}
