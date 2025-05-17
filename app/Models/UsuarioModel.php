<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $DBGroup = 'gestor_tareas';
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'apellido', 'correo', 'contrasena', 'fecha_creacion'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}

