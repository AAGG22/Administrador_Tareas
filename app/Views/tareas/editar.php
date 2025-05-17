<h2>Editar Tarea</h2>
<form method="post" action="/tareas/actualizar/<?= $tarea['id_tarea'] ?>">
    <label>Título:</label><input type="text" name="titulo" value="<?= $tarea['titulo'] ?>"><br>
    <label>Descripción:</label><textarea name="descripcion"><?= $tarea['descripcion'] ?></textarea><br>
    <label>Fecha de vencimiento:</label><input type="date" name="fecha_vencimiento" value="<?= $tarea['fecha_vencimiento'] ?>"><br>
    <button type="submit">Actualizar</button>
</form>
