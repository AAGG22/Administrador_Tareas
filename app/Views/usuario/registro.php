<?= $this->extend('usuario/layout') ?>

<?= $this->section('contenido') ?>
<div class="contenedor-registro">
    <h2>Crear Cuenta</h2>
    
    <?php if (isset($errores)): ?>
        <div class="error">
            <?php foreach ($errores as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('registro') ?>" method="post">
        <div class="campo">
            <label for="nombre_usuario">Nombre de usuario</label>
            <input type="text" name="nombre_usuario" id="nombre_usuario" 
                   value="<?= old('nombre_usuario') ?>" required>
        </div>

        <div class="campo">
            <label for="correo">Correo electrónico</label>
            <input type="email" name="correo" id="correo" 
                   value="<?= old('correo') ?>" required>
        </div>

        <div class="campo">
            <label for="contraseña">Contraseña (mínimo 8 caracteres)</label>
            <input type="password" name="contraseña" id="contraseña" required>
        </div>

        <div class="campo">
            <label for="confirmar_contraseña">Confirmar contraseña</label>
            <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" required>
        </div>

        <div class="campo">
            <label for="nombre_completo">Nombre completo (opcional)</label>
            <input type="text" name="nombre_completo" id="nombre_completo" 
                   value="<?= old('nombre_completo') ?>">
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <div class="enlace-login">
        ¿Ya tienes cuenta? <a href="<?= base_url('login') ?>">Inicia sesión</a>
    </div>
</div>
<?= $this->endSection() ?>