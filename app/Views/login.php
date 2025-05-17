<?php 
   if(isset($error)){
    echo '<br>Usuario '.$user.' NO encontrado - o Password incorrecto '.$pass.'<br><br>'; 
}?>
<?= form_open('navegar/iniciar'); ?>
User: <input type="text" name="user" /><br />
Password: <input type="password" name="pass" /><br />
<input type="submit" name="Enviar" />
<?= form_Close(); ?>