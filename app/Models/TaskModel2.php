<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $DBGroup = 'gestor_tareas';
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'priority', 'status', 'due_date'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'priority' => 'required|in_list[low,medium,high]',
        'status' => 'required|in_list[pending,in_progress,completed]'
    ];


    protected $validationMessages = [
        'title' => [
            'required' => 'El título de la tarea es obligatorio',
            'min_length' => 'El título debe tener al menos {param} caracteres',
            'max_length' => 'El título no puede exceder {param} caracteres'
        ],
        // Puedes agregar mensajes para otros campos
        'priority' => [
            'required' => 'Debes seleccionar una prioridad',
            'in_list' => 'La prioridad seleccionada no es válida'
        ]
    ];



    // Relación con subtareas
    public function getSubtasks($taskId)
    {
        return $this->db->table('subtasks')
            ->where('task_id', $taskId)
            ->get()
            ->getResultArray();
    }
    

    // Verificar subtareas
    public function hasIncompleteSubtasks($taskId)
    {
        return $this->db->table('subtasks')
            ->where('task_id', $taskId)
            ->where('is_completed', 0)
            ->countAllResults() > 0;
    }
}