<h2>Crear Tarea</h2>
<form method="post" action="<?= base_url('tareas/guardar') ?>">

    <label>Título:</label><input type="text" name="titulo"><br>
    <label>Descripción:</label><textarea name="descripcion"></textarea><br>
    <label>Fecha de vencimiento:</label><input type="date" name="fecha_vencimiento"><br>
    <button type="submit">Guardar</button>
</form>
