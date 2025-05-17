<?php
namespace App\Models;
use CodeIgniter\Model;

class SubtaskModel extends Model
{
    protected $DBGroup = 'gestor_tareas';
    protected $table = 'subtasks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['task_id', 'title', 'is_completed'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'task_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]'
    ];
}