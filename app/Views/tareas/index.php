<h2>Listado de Tareas (Views/tareas/index.php)</h2>
<a href="<?= base_url('tareas/crear') ?>">Crear nueva tarea</a>
<ul>
<?php foreach ($tareas as $tarea): ?>
    <li>
        <?= esc($tarea['titulo']) ?> - <?= esc($tarea['fecha_vencimiento']) ?>
        <a href="/tareas/editar/<?= $tarea['id_tarea'] ?>">Editar</a>
        <a href="/tareas/eliminar/<?= $tarea['id_tarea'] ?>">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul>
