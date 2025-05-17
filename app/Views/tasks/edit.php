<?php
$this->extend('layouts/default');
?>

<?php $this->section('content'); ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= esc($title) ?></h1>

    <!-- Mensajes de éxito o error -->
    <?php if (session()->getFlashdata('exito')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('exito') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Mostrar errores de validación -->
    <?php if (session()->has('validation')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session()->get('validation')->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulario de edición -->
    <form action="<?= site_url('tasks/update/' . $task['id']) ?>" method="post" class="mb-4">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="<?= old('title', esc($task['title'])) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= old('description', esc($task['description'] ?? '')) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Prioridad</label>
            <select class="form-control" id="priority" name="priority" required>
                <option value="low" <?= old('priority', $task['priority']) === 'low' ? 'selected' : '' ?>>Baja</option>
                <option value="medium" <?= old('priority', $task['priority']) === 'medium' ? 'selected' : '' ?>>Media</option>
                <option value="high" <?= old('priority', $task['priority']) === 'high' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <?php if ($es_propia): ?>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" <?= old('status', $task['status']) === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="in_progress" <?= old('status', $task['status']) === 'in_progress' ? 'selected' : '' ?>>En Progreso</option>
                    <option value="completed" <?= old('status', $task['status']) === 'completed' ? 'selected' : '' ?>>Completada</option>
                </select>
            <?php else: ?>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" <?= old('status', $task['status']) === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="in_progress" <?= old('status', $task['status']) === 'in_progress' ? 'selected' : '' ?>>En Progreso</option>
                </select>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Fecha de Vencimiento</label>
            <input type="date" class="form-control" id="due_date" name="due_date" 
                   value="<?= old('due_date', esc($task['due_date'] ?? '')) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
        <a href="<?= site_url('tasks') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php $this->endSection(); ?>