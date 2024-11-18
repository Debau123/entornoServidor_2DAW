<h3>Añade una noticia</h3>
<form  method="POST" action="/store">
    @csrf
    <label for="titulo">Titulo</label><br>
    <input name="titulo"><br>
    <label for="cuerpo">Noticia</label><br>
    <textarea name="cuerpo"></textarea><br>
    <label for="enlace">Enlace</label><br>
    <input name="enlace"><br>
    <input type="submit">
</form>