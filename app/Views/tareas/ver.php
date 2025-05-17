<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h2><?= $tarea['titulo'] ?></h2>
    <p><?= $tarea['descripcion'] ?></p>
    <p><strong>Prioridad:</strong> 
        <span class="badge bg-<?= $tarea['id_prioridad'] == 3 ? 'danger' : ($tarea['id_prioridad'] == 2 ? 'warning' : 'success') ?>">
            <?= $tarea['id_prioridad'] == 3 ? 'Alta' : ($tarea['id_prioridad'] == 2 ? 'Normal' : 'Baja') ?>
        </span>
    </p>

    <h4 class="mt-4">Subtareas</h4>
    <ul class="list-group">
        <?php foreach ($subtareas as $subtarea): ?>
        <li class="list-group-item">
            <?= $subtarea['descripcion'] ?>
            <span class="badge bg-secondary float-end"><?= $subtarea['nombre_estado'] ?></span>
        </li>
        <?php endforeach; ?>
    </ul>

    <a href="/tareas" class="btn btn-secondary mt-3">Volver</a>
</div>
<?= $this->endSection() ?>