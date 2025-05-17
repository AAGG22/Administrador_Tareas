<?php
$this->extend('layouts/default');
?>

<?php $this->section('content'); ?>

<style>
    .subtask-completed {
        text-decoration: line-through;
        color: #6c757d;
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4"><?= esc($title) ?></h1>

    <!-- Mensajes de éxito o error -->
    <?php if (session()->getFlashdata('exito')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('exito') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Detalles de la tarea -->
    <div class="card">
        <div class="card-header">
            <h3><?= esc($task['title']) ?></h3>
        </div>
        <div class="card-body">
            <p><strong>Descripción:</strong> <?= $task['description'] ? esc($task['description']) : 'Sin descripción' ?></p>
            <p><strong>Prioridad:</strong> <?= esc(translate_priority($task['priority'])) ?></p>
            <p><strong>Estado:</strong> <?= esc(translate_status($task['status'])) ?></p>
            <p><strong>Fecha de Vencimiento:</strong> <?= $task['due_date'] ? esc($task['due_date']) : 'Sin fecha' ?></p>
            <p><strong>Dueño:</strong> <?= esc($task['correo_dueno']) ?></p>
            <p><strong>Creada el:</strong> <?= esc($task['created_at']) ?></p>
            <p><strong>Actualizada el:</strong> <?= esc($task['updated_at']) ?></p>
        </div>
    </div>

    <!-- Colaboradores -->
    <h4 class="mt-4">Colaboradores</h4>
    <?php if (empty($task['colaboradores'])): ?>
        <p>No hay colaboradores asignados.</p>
    <?php else: ?>
        <ul class="list-group mb-4">
            <?php foreach ($task['colaboradores'] as $colaborador): ?>
                <li class="list-group-item"><?= esc($colaborador['correo_colaborador']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Subtareas -->
    <h4>Subtareas</h4>
    <?php if (empty($task['subtasks'])): ?>
        <p>No hay subtareas asignadas.</p>
    <?php else: ?>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Completada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($task['subtasks'] as $subtask): ?>
                    <tr>
                        <td class="<?= $subtask['is_completed'] ? 'subtask-completed' : '' ?>">
                            <?= esc($subtask['title']) ?>
                        </td>
                        <td><?= $subtask['is_completed'] ? 'Completada' : 'Pendiente' ?></td>
                        <td>
                            <form action="<?= site_url('tasks/toggleSubtask/' . $subtask['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="checkbox" name="is_completed" 
                                       onchange="this.form.submit()" 
                                       <?= $subtask['is_completed'] ? 'checked' : '' ?>>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Formulario para añadir subtarea (solo para el dueño) -->
    <?php if ($es_propia): ?>
        <h4>Añadir Subtarea</h4>
        <form action="<?= site_url('tasks/addSubtask/' . $task['id']) ?>" method="post" class="mb-4">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="subtask_title" class="form-label">Título de la Subtarea</label>
                <input type="text" class="form-control" id="subtask_title" name="subtask_title" required>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Subtarea</button>
        </form>
    <?php endif; ?>

    <!-- Botones de acción -->
    <div class="mt-4">
        <a href="<?= site_url('tasks') ?>" class="btn btn-secondary">Volver a la Lista</a>
        <a href="<?= site_url('tasks/edit/' . $task['id']) ?>" class="btn btn-warning">Editar Tarea</a>
        <?php if ($es_propia): ?>
            <a href="<?= site_url('tasks/delete/' . $task['id']) ?>" class="btn btn-danger" 
               onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar Tarea</a>
            <a href="<?= site_url('tasks/compartir/' . $task['id']) ?>" class="btn btn-secondary">Compartir Tarea</a>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?>