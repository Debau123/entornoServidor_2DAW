<?php
/*
//Parte sin la sesion es decir no se guarda las modificiaciones ni los que creas
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
            <form method='POST' action='/comments/{$commentid}' >
                <input type='hidden' name='_method' value='PATCH'>
                <input  name='comment' value='{$this->comentarios[$commentid]}'>
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
}*/


//Parte con la session para que las modificaciones y los creados se guarden y un html
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
    {
    // Inicializa los comentarios en memoria
    private $comentarios = ["1 comentario", "2 comentario"];
    
    public function index(Request $request)
    {
    // Obtener los comentarios de la sesión
    $comentarios = $request->session()->get('comentarios', $this->comentarios);

    // Iniciar la lista de HTML
    $html = "<ul>";

    // Iterar sobre los comentarios
    foreach ($comentarios as $id => $comentario) {
        $html .= "<li>
                    <a href='/comments/{$id}'>{$comentario}</a>
                    <form method='GET' action='/comments/{$id}/edit' style='display:inline'>
                        <button type='submit'>Modificar</button>
                    </form>
                    <form method='POST' action='/comments/{$id}' style='display:inline'>
                        " . csrf_field() . "
                        <input type='hidden' name='_method' value='DELETE'>
                        <button type='submit'>Eliminar</button>
                    </form>
                  </li>";
    }

    // Cerrar la lista de HTML
    $html .= "</ul>";

    // Botón para ir a la página de crear un nuevo comentario
    $html .= "
        <a href='/comments/create'>
            <button>Crear comentario</button>
        </a>";

    return $html;
    }
    public function create()
    {
        return "<form method='POST' action='/comments'>
                    " . csrf_field() . "
                    <input type='text' name='comment'>
                    <input type='submit' value='Enviar'>
                </form>";
    }

    public function store(Request $request)
    {
        $comentarios = $request->session()->get('comentarios', $this->comentarios);
        array_push($comentarios, $request->input('comment'));
        $request->session()->put('comentarios', $comentarios);

        return redirect('/comments');
    }

    public function show(Request $request, string $commentid)
    {
        $comentarios = $request->session()->get('comentarios', $this->comentarios);
        return $comentarios[$commentid] ?? "Comentario no encontrado";
    }

    public function edit(Request $request, int $commentid)
    {
        $comentarios = $request->session()->get('comentarios', $this->comentarios);
        return "
            <form method='POST' action='/comments/{$commentid}'>
                " . csrf_field() . "
                <input type='hidden' name='_method' value='PATCH'>
                <input  name='comment' value='{$comentarios[$commentid]}'>
                <input type='submit' value='Actualizar'>
            </form>
        ";
    }

    public function update(Request $request, int $commentid)
    {
        $comentarios = $request->session()->get('comentarios', $this->comentarios);
        $comentarios[$commentid] = $request->input('comment');
        $request->session()->put('comentarios', $comentarios);

        return redirect('/comments');
    }

    public function destroy(Request $request, int $commentid)
    {
        $comentarios = $request->session()->get('comentarios', $this->comentarios);
        unset($comentarios[$commentid]);
        $comentarios = array_values($comentarios);
        $request->session()->put('comentarios', $comentarios);

        return redirect('/comments');
    }
}
