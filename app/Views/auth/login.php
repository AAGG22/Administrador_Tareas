<?php
// Incluir la cabecera
echo $cabecera;
?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session('error')) ?>
    </div>
<?php endif ?>

<div class="container mt-4">
    <h2>Iniciar Sesión</h2>
    <form method="post" action="<?= site_url('login') ?>">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?= old('correo') ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
    </form>
</div>

<?php
// Incluir el footer
echo $footer;
?>