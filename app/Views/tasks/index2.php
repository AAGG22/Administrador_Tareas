<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>



<div class="container mt-5">
    <h1 class="mb-4"><?= $title ?></h1>
    
    <a href="<?= site_url('tasks/create') ?>" class="btn btn-primary mb-3">Nueva Tarea</a>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <div class="row">
        <?php foreach ($tasks as $task): ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($task['title']) ?></h5>
                    <p class="card-text">
           
        <!-- Prioridad con color -->
                <span class="badge bg-<?= 
                    $task['priority'] === 'high' ? 'danger' : 
                    ($task['priority'] === 'medium' ? 'warning' : 'success') 
                ?>">
                    <?= translate_priority($task['priority']) ?>
                </span>
                
                <!-- Estado con color -->
                <span class="badge bg-<?= 
                    $task['status'] === 'completed' ? 'success' : 
                    ($task['status'] === 'in_progress' ? 'primary' : 'secondary') 
                ?> ms-2">
                    <?= translate_status($task['status']) ?>
                </span>
        <!-- ------------------------ -->


                    </p>
                    <a href="<?= site_url("tasks/view/{$task['id']}") ?>" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                   <!-- <a href="< //site_url("tasks/delete/{$task['id']}") ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta tarea?')">Eliminar</a> -->
<a href="<?= site_url("tasks/delete/{$task['id']}") ?>" 
   class="btn btn-sm btn-outline-danger <?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'disabled' : '' ?>"
   onclick="<?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'alert(\'No se puede eliminar: tiene subtareas pendientes\'); return false;' : 'return confirm(\'¿Estás seguro de eliminar esta tarea?\')' ?>"
   <?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'title="No se puede eliminar: tiene subtareas pendientes"' : '' ?>>
   Eliminar
</a>
     
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>