<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>Mis Tareas</h1>
    
    <a href="<?= base_url('/tareas/crear') ?>">Nueva Tarea</a>
    
    <?php if (session()->get('mensaje')): ?>
        <div class="alert alert-success"><?= session()->get('mensaje') ?></div>
    <?php endif; ?>
    
    <div class="list-group">
        <?php foreach ($tareas as $tarea): ?>
        <a href="/tareas/ver/<?= $tarea['id_tarea'] ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?= $tarea['titulo'] ?></h5>
                <small class="badge bg-<?= $tarea['nombre_prioridad'] == 'Alta' ? 'danger' : ($tarea['nombre_prioridad'] == 'Normal' ? 'warning' : 'success') ?>">
                    <?= $tarea['nombre_prioridad'] ?>
                </small>
            </div>
            <p class="mb-1"><?= substr($tarea['descripcion'], 0, 100) ?>...</p>
            <small>Estado: <?= $tarea['nombre_estado'] ?></small>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>