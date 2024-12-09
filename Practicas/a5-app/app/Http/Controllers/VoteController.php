<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Registrar un voto positivo (like).
     */
    public function like($id)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $fichero = Fichero::findOrFail($id);

        // Verificar que el archivo no sea privado
        if ($fichero->privado) {
            return response()->json(['message' => 'No se puede votar por archivos privados'], 403);
        }

        // Eliminar el voto existente del usuario (si lo hay)
        $fichero->votes()->where('user_id', Auth::id())->delete();

        // Crear un nuevo voto positivo
        $fichero->votes()->create([
            'user_id' => Auth::id(),
            'like' => true,
        ]);

        return response()->json(['message' => 'Voto positivo registrado']);
    }

    /**
     * Registrar un voto negativo (dislike).
     */
    public function dislike($id)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $fichero = Fichero::findOrFail($id);

        // Verificar que el archivo no sea privado
        if ($fichero->privado) {
            return response()->json(['message' => 'No se puede votar por archivos privados'], 403);
        }

        // Eliminar el voto existente del usuario (si lo hay)
        $fichero->votes()->where('user_id', Auth::id())->delete();

        // Crear un nuevo voto negativo
        $fichero->votes()->create([
            'user_id' => Auth::id(),
            'like' => false,
        ]);

        return response()->json(['message' => 'Voto negativo registrado']);
    }
}
