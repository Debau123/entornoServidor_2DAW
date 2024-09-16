<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CommentController extends Controller
{
    // Inicializa los comentarios, pero utiliza la sesión para persistirlos
    public $comentarios = ["1 comentario", "2 comentario"];

    // Muestra los comentarios desde la sesión
    function index()
    {
        return $this->comentarios;
    }
    // Formulario para crear comentarios
    function create()
    {
        return "<form method='POST' action='/comments'>
                    <input type='text' name='comment'>
                    <input type='submit' value='Enviar'>
                </form>";
    }
    // Almacena el comentario en la sesión
    function store(Request $request)
    {

     // Agregar el comentario 
     array_push($this->comentarios, $request->input('comment'));

     // Devuelve el array actualizado (solo durante esta solicitud)
     return $this->comentarios;
       
    }
    // Buscar por id  
    function show(string $commentid)
    {
        
        return $this->comentarios[$commentid];     
    }
    //Editar un comentario
    function edit(int $commentid)
    {
        // Simplemente devuelve un formulario HTML para editar el comentario
        return "
            <form method='POST' action='/comments/{$commentid}' enctype='multipart/form-data'>
                <input type='hidden' name='_method' value='PATCH'>
                <input type='text' name='comment' value='{$this->comentarios[$commentid]}'>
                <input type='submit' value='Actualizar'>
            </form>
        ";
    }
    //Para que muestre la actualizacion
    function update(Request $request, int $commentid)
    {
        // Actualizar el comentario en el array temporal
        $this->comentarios[$commentid] = $request->input('comment');

        // Devolver el array actualizado de comentarios
        return $this->comentarios;
    }
    //Para que elimine un comentario
    public function destroy(int $commentid)
    {
        // Eliminar el comentario en la posición dada
        unset($this->comentarios[$commentid]);
        // Re-indexar el array para mantener los índices consecutivos
        $this->comentarios = array_values($this->comentarios);
        // Devolver el array actualizado de comentarios
        return $this->comentarios;
    }
}