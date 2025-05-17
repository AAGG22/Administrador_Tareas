<?php
echo form_open('form/exito');
echo form_label('Nombre: ', 'nombre');
echo form_input(array('name' => 'nombre', 'value' => old('nombre'))) . '<br>';
echo '<p style="color:red">' . session('errors.nombre') . '</p>';

echo form_label('Password: ', 'password');
echo form_password(array('name' => 'password', 'value' => old('password'))) . '<br>';
echo '<p style="color:red">' . session('errors.password') . '</p>';
echo form_label('Reingrese el Password: ', 'c_password');
echo form_password(array('name' => 'c_password')) . '<br>';
echo form_label('Correo Electrónico: ', 'email');
echo form_input(array('name' => 'email', 'type' => 'email'));
echo form_submit('enviar', 'Enviar');
echo form_reset('reset', 'Limpiar Formulario');
echo form_close();
