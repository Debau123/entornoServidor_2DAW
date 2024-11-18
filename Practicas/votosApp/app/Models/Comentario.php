<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentario extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'noticia_id', 'user_id'];

    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    
}
