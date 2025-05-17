<?php
// Incluir la cabecera
echo $cabecera;
?>

<div class="container mt-4">
    <?php if (session()->has('exito')): ?>
        <div class="alert alert-success">
            <?= esc(session('exito')) ?>
        </div>
    <?php endif ?>

    <h2>Perfil</h2>
    <p><strong>Correo:</strong> <?= esc(session('correo')) ?></p>
    <a href="<?= site_url('logout') ?>" class="btn btn-danger">Cerrar Sesión</a>
</div>

<?php
// Incluir el footer
echo $footer;
?>