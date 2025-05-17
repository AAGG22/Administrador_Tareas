<?php
namespace App\Models;
use CodeIgniter\Model;

class TareaCompartidaModel extends Model
{
    protected $DBGroup = 'gestor_tareas';
    protected $table = 'tarea_compartida';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'correo_colaborador', 'fecha_compartido'];
    protected $useTimestamps = true;
    protected $createdField = 'fecha_compartido';
    protected $updatedField = '';

    protected $validationRules = [
        'task_id' => 'required|integer',
        'correo_colaborador' => 'required|valid_email'
    ];

    protected $validationMessages = [
        'correo_colaborador' => [
            'valid_email' => 'El correo del colaborador debe ser válido.'
        ]
    ];
}