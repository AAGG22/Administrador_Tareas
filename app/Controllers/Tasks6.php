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
        helper('task');
        $this->taskModel = new TaskModel();
        $this->subtaskModel = new SubtaskModel();
    }

    public function index()
    {
        /* Obtener parámetros de ordenamiento desde la URL */
        $sortBy = $this->request->getGet('sort_by') ?? 'created_at';
        $sortOrder = $this->request->getGet('sort_order') ?? 'desc';

        /* Obtener tareas ordenadas */
        $tasks = $this->taskModel->getOrderedTasks($sortBy, $sortOrder);
        
        /* Prepara los datos adicionales para cada tarea */
        $processedTasks = [];
        foreach ($tasks as $task) {
            $task['has_incomplete_subtasks'] = $this->taskModel->hasIncompleteSubtasks($task['id']);
            $task['subtasks'] = $this->taskModel->getSubtasks($task['id']);
            $processedTasks[] = $task;
        }

        $data = [
            'tasks' => $processedTasks,
            'title' => 'Panel de Tareas',
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder
        ];

        return view('tasks/index', $data);
    }

    public function view($id)
    {
        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($id);
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

    public function create()
    {
        $data = ['title' => 'Crear Nueva Tarea'];

        if ($this->request->getMethod() === 'post') {
            $postData = $this->request->getPost();
            $postData['user_id'] = session()->get('user_id');
            if ($this->taskModel->save($postData)) {
                return redirect()->to('/tasks')->with('success', 'Tarea creada exitosamente');
            }
            $data['validation'] = $this->taskModel->errors();
        }

        return view('tasks/create', $data);
    }

    public function edit($id)
    {
        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($id);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea no encontrada');
        }

        $data = [
            'task' => $task,
            'title' => 'Editar Tarea'
        ];

        return view('tasks/edit', $data);
    }

    public function update($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to("/tasks/edit/$id");
        }

        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($id);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea no encontrada');
        }

        $postData = $this->request->getPost();
        $postData['user_id'] = session()->get('user_id');
        if ($this->taskModel->update($id, $postData)) {
            return redirect()->to('/tasks')->with('success', 'Tarea actualizada exitosamente');
        }

        return redirect()->to("/tasks/edit/$id")
            ->withInput()
            ->with('errors', $this->taskModel->errors());
    }

    public function addSubtask($taskId)
    {
        if (!$this->request->is('post')) {
            return redirect()->to("/tasks/view/$taskId");
        }

        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($taskId);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea no encontrada');
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

    public function editSubtask($subtaskId)
    {
        $subtask = $this->subtaskModel->find($subtaskId);
        if (!$subtask) {
            return redirect()->to('/tasks')->with('error', 'Subtarea no encontrada');
        }

        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($subtask['task_id']);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea asociada no encontrada');
        }

        $data = [
            'subtask' => $subtask,
            'task' => $task,
            'title' => 'Editar Subtarea'
        ];

        return view('tasks/edit_subtask', $data);
    }

    public function updateSubtask($subtaskId)
    {
        if (!$this->request->is('post')) {
            return redirect()->to("/tasks/editSubtask/$subtaskId");
        }

        $subtask = $this->subtaskModel->find($subtaskId);
        if (!$subtask) {
            return redirect()->to('/tasks')->with('error', 'Subtarea no encontrada');
        }

        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($subtask['task_id']);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea asociada no encontrada');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->to("/tasks/editSubtask/$subtaskId")
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'is_completed' => $this->request->getPost('is_completed') ? 1 : 0
        ];

        if ($this->subtaskModel->update($subtaskId, $data)) {
            return redirect()->to("/tasks/view/{$subtask['task_id']}")->with('success', 'Subtarea actualizada');
        }

        return redirect()->to("/tasks/editSubtask/$subtaskId")
            ->withInput()
            ->with('errors', $this->subtaskModel->errors());
    }

    public function toggleSubtask($subtaskId)
    {
        $subtask = $this->subtaskModel->find($subtaskId);
        if (!$subtask) {
            return redirect()->back()->with('error', 'Subtarea no encontrada');
        }

        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($subtask['task_id']);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea asociada no encontrada');
        }

        $newStatus = $subtask['is_completed'] ? 0 : 1;
        $this->subtaskModel->update($subtaskId, ['is_completed' => $newStatus]);

        return redirect()->to("/tasks/view/{$subtask['task_id']}")->with('success', 'Subtarea actualizada');
    }

    public function delete($id)
    {
        $task = $this->taskModel->where('user_id', session()->get('user_id'))->find($id);
        if (!$task) {
            return redirect()->to('/tasks')->with('error', 'Tarea no encontrada');
        }

        $incompleteSubtasks = $this->subtaskModel->where('task_id', $id)
            ->where('is_completed', 0)
            ->countAllResults();

        if ($incompleteSubtasks > 0) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar la tarea porque tiene subtareas pendientes');
        }

        $this->taskModel->delete($id);

        return redirect()->to('/tasks')
            ->with('success', 'Tarea eliminada correctamente');
    }

    public function store()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/tasks/create');
        }

        $data = $this->request->getPost();
        $data['user_id'] = session()->get('user_id');

        if ($this->taskModel->save($data)) {
            return redirect()->to('/tasks')->with('success', 'Tarea creada exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('errors', $this->taskModel->errors());
    }
}