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
    
    <form method="post" action="<?= site_url('tasks/compartir/' . $task['id']) ?>">
        <div class="mb-3">
            <label for="correo_colaborador" class="form-label">Correo del Colaborador</label>
            <input type="email" name="correo_colaborador" id="correo_colaborador" class="form-control" 
                   value="<?= old('correo_colaborador') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Compartir</button>
        <a href="<?= site_url('tasks') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php $this->endSection(); ?>