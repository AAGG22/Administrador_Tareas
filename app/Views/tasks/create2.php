<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4"><?= $title ?></h1>
    
    <?php if (isset($validation)): ?>
        <div class="alert alert-danger">
            <?= $validation->listErrors() ?>
        </div>
    <?php endif; ?>
    
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="priority" class="form-label">Prioridad</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="low">Baja</option>
                    <option value="medium" selected>Media</option>
                    <option value="high">Alta</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="pending" selected>Pendiente</option>
                    <option value="in_progress">En progreso</option>
                    <option value="completed">Completado</option>
                </select>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="due_date" class="form-label">Fecha límite (opcional)</label>
            <input type="date" class="form-control" id="due_date" name="due_date">
        </div>
        
        <button type="submit" class="btn btn-primary">Crear Tarea</button>
        <a href="<?= site_url('tasks') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?= $this->endSection() ?>

//Mostrar errores
<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <?php foreach (session('errors') as $error) : ?>
            <p><?= $error ?></p>
        <?php endforeach ?>
    </div>
<?php endif; ?>