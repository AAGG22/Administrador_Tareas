<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\SubtaskModel;

class Tasks extends BaseController
{
    protected $taskModel;
    protected $subtaskModel;

    public function __construct()
    {
        helper('task'); // Carga el helper (sin .php)
        $this->taskModel = new TaskModel();
        $this->subtaskModel = new SubtaskModel();
    }

public function index()
{
    $tasks = $this->taskModel->findAll();
    
    // Prepara los datos adicionales para cada tarea
    $processedTasks = [];
    foreach ($tasks as $task) {
        $task['has_incomplete_subtasks'] = $this->taskModel->hasIncompleteSubtasks($task['id']);
        $processedTasks[] = $task;
    }

    $data = [
        'tasks' => $processedTasks, // Usamos el array procesado
        'title' => 'Panel de Tareas 01'
    ];

    return view('tasks/index', $data);
}

    // Ver tarea con sus subtareas
    public function view($id)
    {
        $task = $this->taskModel->find($id);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea no encontrada');
        }

        $data = [
            'task' => $task,
            'subtasks' => $this->taskModel->getSubtasks($id),
            'title' => 'Detalles de Tarea'
        ];

        return view('tasks/view', $data);
    }

    // Crear nueva tarea
    public function create()
    {
        $data = ['title' => 'Crear Nueva Tarea'];

        if ($this->request->getMethod() === 'post') {
            if ($this->taskModel->save($this->request->getPost())) {
                return redirect()->to('/tasks')->with('success', 'Tarea creada exitosamente');
            }
            $data['validation'] = $this->taskModel->errors();
        }

        return view('tasks/create', $data);
    }

    /* // Añadir subtarea
    public function addSubtask($taskId)
    {
        if ($this->request->getMethod() === 'post') {
            $postData = $this->request->getPost();
            $postData['task_id'] = $taskId;

            if ($this->subtaskModel->save($postData)) {
                return redirect()->to("/tasks/view/$taskId")->with('success', 'Subtarea añadida');
            }
            return redirect()->to("/tasks/view/$taskId")->with('error', $this->subtaskModel->errors());
        }

        return redirect()->to("/tasks/view/$taskId");
    }
    */

    public function addSubtask($taskId)
    {
        if (!$this->request->is('post')) {
            return redirect()->to("/tasks/view/$taskId");
        }

        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->to("/tasks/view/$taskId")
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'task_id' => $taskId,
            'title' => $this->request->getPost('title'),
            'is_completed' => 0
        ];

        if ($this->subtaskModel->save($data)) {
            return redirect()->to("/tasks/view/$taskId")->with('success', 'Subtarea añadida');
        }

        return redirect()->to("/tasks/view/$taskId")
            ->withInput()
            ->with('errors', $this->subtaskModel->errors());
    }

    // Marcar subtarea como completada/incompleta
    public function toggleSubtask($subtaskId)
    {
        $subtask = $this->subtaskModel->find($subtaskId);
        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtarea no encontrada');
        }

        $newStatus = $subtask['is_completed'] ? 0 : 1;
        $this->subtaskModel->update($subtaskId, ['is_completed' => $newStatus]);

        return redirect()->to("/tasks/view/{$subtask['task_id']}")->with('success', 'Subtarea actualizada');
    }

    public function delete($id)
    {
        // Verificar si hay subtareas incompletas
        $incompleteSubtasks = $this->subtaskModel->where('task_id', $id)
            ->where('is_completed', 0)
            ->countAllResults();

        if ($incompleteSubtasks > 0) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar la tarea porque tiene subtareas pendientes');
        }

        // Si no hay subtareas pendientes, eliminar
        $this->taskModel->delete($id);

        return redirect()->to('/tasks')
            ->with('success', 'Tarea eliminada correctamente');
    }
    /*
    // Eliminar tarea (deja eliminar tareas aunque tenga subtareas)
    public function delete($id)
    {
        $this->taskModel->delete($id);
        return redirect()->to('/tasks')->with('success', 'Tarea eliminada');
    }
*/
    // Método para procesar el POST del formulario
    /* public function store()
    {
        if ($this->request->getMethod() === 'post') {
            if ($this->taskModel->save($this->request->getPost())) {
                return redirect()->to('/tasks')->with('success', 'Tarea creada exitosamente');
            }
            return redirect()->back()->withInput()->with('errors', $this->taskModel->errors());
        }
        return redirect()->to('/tasks/create');
    }
        */
    /*
public function store()
{
    if ($this->request->getMethod() === 'post') {
        if (!$this->taskModel->save($this->request->getPost())) {
            return redirect()->back()
                          ->withInput()
                          ->with('errors', $this->taskModel->errors());
        }
        return redirect()->to('/tasks')->with('success', 'Tarea creada');
    }
    return redirect()->to('/tasks/create');
}

*/


    public function store()
    {
        // Verifica que sea una petición POST
        if (!$this->request->is('post')) {
            return redirect()->to('/tasks/create');
        }

        // Obtiene los datos del formulario
        $data = $this->request->getPost();

        // Intenta guardar la tarea
        if ($this->taskModel->save($data)) {
            return redirect()->to('/tasks')->with('success', 'Tarea creada exitosamente');
        }

        // Si hay errores, regresa al formulario con los errores y datos
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->taskModel->errors());
    }
}
