<h1>Bienvenido a P&aacute;gina 1 <?php // echo $_SESSION['user'];?></h1>
<?php 

if(!isset($_SESSION['navegar'])) $_SESSION['navegar']=1;
       else $_SESSION['navegar']++;
?>
<h2><a href="<?=site_url('navegar/pagina2');?>">Visitar P&aacute;gina 2</a></h2>

<h2><a href="<?=site_url('navegar/principal');?>">Volver a P&aacute;gina Principal</a></h2>

<h2>Si desea salir de su Sesi&oacute;n, <a href="<?=site_url('navegar/cerrar');?>">clickee aqu&iacute;</a></h2>

<h2>Nevegaci&oacute;n: <?php echo $_SESSION['navegar']; ?> </h2>