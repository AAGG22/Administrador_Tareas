<?php $this->extend('layouts/default'); ?>

<?php $this->section('content'); ?>

<div class="container mt-5">
    <h1 class="mb-4">Editar Subtarea</h1>
    
    <!-- Mensajes de error -->
    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session('errors') as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
    
    <!-- Formulario de edición -->
    <form method="post" action="<?= site_url("tasks/updateSubtask/{$subtask['id']}") ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Título de la Subtarea</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= esc($subtask['title']) ?>" required>
        </div>
        
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_completed" name="is_completed" <?= $subtask['is_completed'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_completed">Completada</label>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?= site_url("tasks/view/{$task['id']}") ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php $this->endSection(); ?>