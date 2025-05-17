<?= $this->extend('usuario/layout') ?>

<?= $this->section('contenido') ?>
<h1>PRUEBA DE LOGIN</h1>
<form>
    <input type="text" placeholder="Email">
    <input type="password" placeholder="Contraseña">
    <button>Entrar</button>
</form>
<?= $this->endSection() ?>