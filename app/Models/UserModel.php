<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup = 'gestor_tareas'; // Usa la conexión gestor_tareas
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'apellido', 'correo', 'contrasena'];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField = '';

    protected $validationRules = [
        'nombre' => 'required|min_length[2]|max_length[50]',
        'apellido' => 'required|min_length[2]|max_length[50]',
        'correo' => 'required|valid_email|is_unique[usuario.correo]',
        'contrasena' => 'required|min_length[8]'
    ];

    protected $validationMessages = [
        'correo' => [
            'is_unique' => 'El correo ya está registrado.'
        ]
    ];
}

