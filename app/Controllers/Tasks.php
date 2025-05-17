<?php

namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\SubtaskModel;
use App\Models\TareaCompartidaModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Tasks extends Controller
{
    public function index()
    {
        log_message('debug', 'Accediendo a Tasks::index');
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $usuarioModelo = new UsuarioModel();

        $userId = session()->get('user_id');
        log_message('debug', 'User ID en sesión: ' . ($userId ?? 'null'));

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para ver tus tareas.');
            return redirect()->to('login');
        }

        // Obtener correo del usuario logueado
        $correoLogueado = session()->get('correo') ?? '';
        log_message('debug', 'Correo logueado: ' . ($correoLogueado ?: 'vacío'));

        // Manejar ordenamiento
        $sortBy = $this->request->getGet('sort_by') ?: 'title';
        $sortOrder = $this->request->getGet('sort_order') ?: 'asc';
        $allowedSorts = ['title', 'priority', 'status', 'due_date', 'created_at'];
        $allowedOrders = ['asc', 'desc'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'title';
        $sortOrder = in_array($sortOrder, $allowedOrders) ? $sortOrder : 'asc';

        // Obtener tareas propias (no archivadas)
        $tareasPropias = $modelo->where('user_id', $userId)
                               ->where('is_archived', 0)
                               ->orderBy($sortBy, $sortOrder)
                               ->findAll();
        log_message('debug', 'Tareas propias encontradas: ' . count($tareasPropias));

        // Obtener tareas compartidas (no archivadas)
        $correo = session()->get('correo') ?? '';
        log_message('debug', 'Correo en sesión: ' . ($correo ?: 'vacío'));
        $tareasCompartidas = $modelo->select('tasks.*')
                                   ->join('tarea_compartida', 'tarea_compartida.task_id = tasks.id')
                                   ->where('tarea_compartida.correo_colaborador', $correo)
                                   ->where('tasks.is_archived', 0)
                                   ->orderBy($sortBy, $sortOrder)
                                   ->findAll();
        log_message('debug', 'Tareas compartidas encontradas: ' . count($tareasCompartidas));

        // Combinar tareas evitando duplicados por ID
        $tareas = [];
        $taskIds = [];

        foreach ($tareasPropias as $tarea) {
            $tarea['es_propia'] = true;
            // Obtener correo del dueño
            $usuario = $usuarioModelo->select('correo')->find($tarea['user_id']);
            $tarea['correo_dueno'] = $usuario['correo'] ?? 'Desconocido';
            $tareas[] = $tarea;
            $taskIds[$tarea['id']] = true;
        }

        foreach ($tareasCompartidas as $tarea) {
            if (!isset($taskIds[$tarea['id']])) {
                $tarea['es_propia'] = false;
                // Obtener correo del dueño
                $usuario = $usuarioModelo->select('correo')->find($tarea['user_id']);
                $tarea['correo_dueno'] = $usuario['correo'] ?? 'Desconocido';
                $tareas[] = $tarea;
                $taskIds[$tarea['id']] = true;
            }
        }

        // Añadir subtareas y verificar subtareas pendientes
        foreach ($tareas as &$tarea) {
            $tarea['subtasks'] = $subtaskModelo->where('task_id', $tarea['id'])->findAll();
            $tarea['has_incomplete_subtasks'] = $subtaskModelo->where('task_id', $tarea['id'])
                                                             ->where('is_completed', 0)
                                                             ->countAllResults() > 0;
        }

        log_message('debug', 'Total de tareas combinadas: ' . count($tareas));

        $data['tasks'] = $tareas;
        $data['title'] = 'Lista de Tareas';
        $data['sort_by'] = $sortBy;
        $data['sort_order'] = $sortOrder;
        $data['correo_logueado'] = $correoLogueado;
        $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');

        return view('tasks/index', $data);
        
    }

    public function archived()
    {
        log_message('debug', 'Accediendo a Tasks::archived');
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $usuarioModelo = new UsuarioModel();

        $userId = session()->get('user_id');
        log_message('debug', 'User ID en sesión: ' . ($userId ?? 'null'));

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para ver tus tareas archivadas.');
            return redirect()->to('login');
        }

        // Obtener correo del usuario logueado
        $correoLogueado = session()->get('correo') ?? '';
        log_message('debug', 'Correo logueado: ' . ($correoLogueado ?: 'vacío'));

        // Obtener tareas propias archivadas
        $tareasPropias = $modelo->where('user_id', $userId)
                               ->where('is_archived', 1)
                               ->orderBy('title', 'asc')
                               ->findAll();
        log_message('debug', 'Tareas propias archivadas encontradas: ' . count($tareasPropias));

        // Obtener tareas compartidas archivadas
        $correo = session()->get('correo') ?? '';
        log_message('debug', 'Correo en sesión: ' . ($correo ?: 'vacío'));
        $tareasCompartidas = $modelo->select('tasks.*')
                                   ->join('tarea_compartida', 'tarea_compartida.task_id = tasks.id')
                                   ->where('tarea_compartida.correo_colaborador', $correo)
                                   ->where('tasks.is_archived', 1)
                                   ->orderBy('title', 'asc')
                                   ->findAll();
        log_message('debug', 'Tareas compartidas archivadas encontradas: ' . count($tareasCompartidas));

        // Combinar tareas evitando duplicados por ID
        $tareas = [];
        $taskIds = [];

        foreach ($tareasPropias as $tarea) {
            $tarea['es_propia'] = true;
            $usuario = $usuarioModelo->select('correo')->find($tarea['user_id']);
            $tarea['correo_dueno'] = $usuario['correo'] ?? 'Desconocido';
            $tareas[] = $tarea;
            $taskIds[$tarea['id']] = true;
        }

        foreach ($tareasCompartidas as $tarea) {
            if (!isset($taskIds[$tarea['id']])) {
                $tarea['es_propia'] = false;
                $usuario = $usuarioModelo->select('correo')->find($tarea['user_id']);
                $tarea['correo_dueno'] = $usuario['correo'] ?? 'Desconocido';
                $tareas[] = $tarea;
                $taskIds[$tarea['id']] = true;
            }
        }

        // Añadir subtareas
        foreach ($tareas as &$tarea) {
            $tarea['subtasks'] = $subtaskModelo->where('task_id', $tarea['id'])->findAll();
        }

        log_message('debug', 'Total de tareas archivadas combinadas: ' . count($tareas));

        $data['tasks'] = $tareas;
        $data['title'] = 'Tareas Archivadas';
        $data['correo_logueado'] = $correoLogueado;
        $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');
        return view('tasks/archived', $data);
    }

    public function create()
    {
        log_message('debug', 'Accediendo a Tasks::create');
        if (!session()->get('user_id')) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para crear tareas.');
            return redirect()->to('login');
        }

        $data['title'] = 'Crear Tarea';
         $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');
        return view('tasks/create', $data);
    }

    public function store()
    {
        log_message('debug', 'Accediendo a Tasks::store');
        $modelo = new TaskModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para crear tareas.');
            return redirect()->to('login');
        }

        // Definir reglas de validación
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'priority' => 'required|in_list[low,medium,high]',
            'due_date' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Errores de validación: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'user_id' => $userId,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description') ?: null,
            'priority' => $this->request->getPost('priority'),
            'status' => 'pending', // Estado fijo al crear
            'due_date' => $this->request->getPost('due_date') ?: null,
            'is_archived' => 0
        ];

        log_message('debug', 'Datos de tarea: ' . print_r($data, true));

        if ($modelo->save($data)) {
            log_message('debug', 'Tarea guardada exitosamente');
            session()->setFlashdata('exito', 'Tarea creada correctamente.');
            return redirect()->to('tasks');
        } else {
            log_message('error', 'Error al guardar tarea: ' . print_r($modelo->errors(), true));
            session()->setFlashdata('error', 'Error al crear la tarea: ' . implode(', ', $modelo->errors()));
            return redirect()->back()->withInput();
        }
    }

    public function view($id)
    {
        log_message('debug', 'Accediendo a Tasks::view, id: ' . $id);
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $usuarioModelo = new UsuarioModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para ver los detalles de la tarea.');
            return redirect()->to('login');
        }

        // Buscar tarea (propia o compartida)
        $tarea = $modelo->find($id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada, id: ' . $id);
            session()->setFlashdata('error', 'Tarea no encontrada.');
            return redirect()->to('tasks');
        }

        // Verificar si el usuario tiene acceso (es dueño o colaborador)
        $esPropia = $tarea['user_id'] == $userId;
        $esCompartida = $tareaCompartidaModelo->where('task_id', $id)
                                             ->where('correo_colaborador', session()->get('correo'))
                                             ->first();
        if (!$esPropia && !$esCompartida) {
            log_message('error', 'Usuario no tiene permiso para ver la tarea, id: ' . $id);
            session()->setFlashdata('error', 'No tienes permiso para ver esta tarea.');
            return redirect()->to('tasks');
        }

        // Obtener correo del dueño
        $usuario = $usuarioModelo->select('correo')->find($tarea['user_id']);
        $tarea['correo_dueno'] = $usuario['correo'] ?? 'Desconocido';

        // Obtener subtareas
        $tarea['subtasks'] = $subtaskModelo->where('task_id', $id)->findAll();

        // Obtener colaboradores
        $tarea['colaboradores'] = $tareaCompartidaModelo->where('task_id', $id)
                                                       ->findAll();

        $data['task'] = $tarea;
        $data['title'] = 'Detalles de la Tarea';
        $data['es_propia'] = $esPropia;
        $data['es_compartida'] = !!$esCompartida;

        return view('tasks/view', $data);
    }

    public function edit($id)
    {
        log_message('debug', 'Accediendo a Tasks::edit, id: ' . $id);
        $modelo = new TaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para editar tareas.');
            return redirect()->to('login');
        }

        $tarea = $modelo->find($id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada, id: ' . $id);
            session()->setFlashdata('error', 'Tarea no encontrada.');
            return redirect()->to('tasks');
        }

        // Verificar si el usuario es dueño o colaborador
        $esPropia = $tarea['user_id'] == $userId;
        $esCompartida = $tareaCompartidaModelo->where('task_id', $id)
                                             ->where('correo_colaborador', session()->get('correo'))
                                             ->first();
        if (!$esPropia && !$esCompartida) {
            log_message('error', 'Usuario no tiene permiso para editar la tarea, id: ' . $id);
            session()->setFlashdata('error', 'No tienes permiso para editar esta tarea.');
            return redirect()->to('tasks');
        }

        // Restricción: Solo el dueño puede editar tareas archivadas
        if ($tarea['is_archived'] == 1 && !$esPropia) {
            log_message('error', 'Los colaboradores no pueden editar tareas archivadas, id: ' . $id);
            session()->setFlashdata('error', 'Solo el dueño puede editar tareas archivadas.');
            return redirect()->to('tasks');
        }

        $data['task'] = $tarea;
        $data['title'] = 'Editar Tarea';
        $data['es_propia'] = $esPropia;
        return view('tasks/edit', $data);
    }

    public function update($id)
    {
        log_message('debug', 'Accediendo a Tasks::update, id: ' . $id);
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para actualizar tareas.');
            return redirect()->to('login');
        }

        $tarea = $modelo->find($id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada, id: ' . $id);
            session()->setFlashdata('error', 'Tarea no encontrada.');
            return redirect()->to('tasks');
        }

        // Verificar si el usuario es dueño o colaborador
        $esPropia = $tarea['user_id'] == $userId;
        $esCompartida = $tareaCompartidaModelo->where('task_id', $id)
                                             ->where('correo_colaborador', session()->get('correo'))
                                             ->first();
        if (!$esPropia && !$esCompartida) {
            log_message('error', 'Usuario no tiene permiso para actualizar la tarea, id: ' . $id);
            session()->setFlashdata('error', 'No tienes permiso para actualizar esta tarea.');
            return redirect()->to('tasks');
        }

        // Restricción: Solo el dueño puede editar tareas archivadas
        if ($tarea['is_archived'] == 1 && !$esPropia) {
            log_message('error', 'Los colaboradores no pueden actualizar tareas archivadas, id: ' . $id);
            session()->setFlashdata('error', 'Solo el dueño puede editar tareas archivadas.');
            return redirect()->to('tasks');
        }

        // Definir reglas de validación
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]',
            'priority' => 'required|in_list[low,medium,high]',
            'status' => 'required|in_list[pending,in_progress,completed]',
            'due_date' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'Errores de validación: ' . print_r($this->validator->getErrors(), true));
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $newStatus = trim($this->request->getPost('status'));
        log_message('debug', 'Nuevo estado recibido: [' . $newStatus . ']');
        log_message('debug', 'Comparación con "completed": ' . ($newStatus === 'completed' ? 'true' : 'false'));

        // Restricción: Solo el dueño puede marcar como completada
        if ($newStatus === 'completed' && !$esPropia) {
            log_message('error', 'Solo el dueño puede marcar la tarea como completada, id: ' . $id);
            session()->setFlashdata('error', 'Solo el dueño puede marcar la tarea como completada.');
            return redirect()->back()->withInput();
        }

        // Restricción: No se puede marcar como completada si hay subtareas pendientes
        if ($newStatus === 'completed') {
            $hasIncompleteSubtasks = $subtaskModelo->where('task_id', $id)
                                                  ->where('is_completed', 0)
                                                  ->countAllResults() > 0;
            log_message('debug', 'Subtareas incompletas: ' . ($hasIncompleteSubtasks ? 'sí' : 'no'));
            if ($hasIncompleteSubtasks) {
                log_message('error', 'No se puede completar la tarea porque tiene subtareas pendientes, id: ' . $id);
                session()->setFlashdata('error', 'No se puede completar la tarea porque tiene subtareas pendientes.');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description') ?: null,
            'priority' => $this->request->getPost('priority'),
            'status' => $newStatus,
            'due_date' => $this->request->getPost('due_date') ?: null,
            'is_archived' => $newStatus === 'completed' ? 1 : 0
        ];

        log_message('debug', 'Datos para actualizar tarea: ' . print_r($data, true));

        try {
            $result = $modelo->update($id, $data);
            $query = $modelo->db->getLastQuery();
            log_message('debug', 'Consulta SQL ejecutada: ' . ($query ? $query->getQuery() : 'No se generó consulta'));

            if ($result) {
                log_message('debug', 'Tarea actualizada exitosamente, id: ' . $id . ', is_archived: ' . $data['is_archived']);
                // Verificar el estado actual en la base de datos
                $updatedTask = $modelo->find($id);
                log_message('debug', 'Estado actual de la tarea tras actualización: ' . print_r($updatedTask, true));
                session()->setFlashdata('exito', 'Tarea actualizada correctamente.');
                return redirect()->to('tasks');
            } else {
                log_message('error', 'Error al actualizar tarea: ' . print_r($modelo->errors(), true));
                session()->setFlashdata('error', 'Error al actualizar la tarea: ' . implode(', ', $modelo->errors()));
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al actualizar tarea, id: ' . $id . ', mensaje: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error inesperado al actualizar la tarea: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        log_message('debug', 'Accediendo a Tasks::delete, id: ' . $id);
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para eliminar tareas.');
            return redirect()->to('login');
        }

        $tarea = $modelo->where('user_id', $userId)->find($id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada o no pertenece al usuario, id: ' . $id);
            session()->setFlashdata('error', 'Tarea no encontrada o no tienes permiso.');
            return redirect()->to('tasks');
        }

        // Verificar si la tarea tiene subtareas
        $subtaskCount = $subtaskModelo->where('task_id', $id)->countAllResults();
        if ($subtaskCount > 0) {
            log_message('error', 'No se puede eliminar la tarea porque tiene subtareas asociadas, id: ' . $id);
            session()->setFlashdata('error', 'No puedes eliminar esta tarea porque tiene subtareas asociadas.');
            return redirect()->to('tasks');
        }

        if ($modelo->delete($id)) {
            log_message('debug', 'Tarea eliminada exitosamente, id: ' . $id);
            session()->setFlashdata('exito', 'Tarea eliminada correctamente.');
        } else {
            log_message('error', 'Error al eliminar tarea: ' . print_r($modelo->errors(), true));
            session()->setFlashdata('error', 'Error al eliminar la tarea.');
        }
        return redirect()->to('tasks');
    }

    public function addSubtask($task_id)
    {
        log_message('debug', 'Accediendo a Tasks::addSubtask, task_id: ' . $task_id);
        $modelo = new TaskModel();
        $subtaskModelo = new SubtaskModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para añadir subtareas.');
            return redirect()->to('login');
        }

        $tarea = $modelo->where('user_id', $userId)->find($task_id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada o no pertenece al usuario, task_id: ' . $task_id);
            session()->setFlashdata('error', 'Tarea no encontrada o no tienes permiso.');
            return redirect()->to('tasks');
        }

        $data = [
            'task_id' => $task_id,
            'title' => $this->request->getPost('subtask_title') ?: 'Subtarea',
            'is_completed' => 0
        ];

        if ($subtaskModelo->save($data)) {
            log_message('debug', 'Subtarea añadida exitosamente, task_id: ' . $task_id);
            session()->setFlashdata('exito', 'Subtarea añadida correctamente.');
        } else {
            log_message('error', 'Error al añadir subtarea: ' . print_r($subtaskModelo->errors(), true));
            session()->setFlashdata('error', 'Error al añadir la subtarea.');
        }
        return redirect()->to('tasks');
    }

    public function toggleSubtask($subtask_id)
    {
        log_message('debug', 'Accediendo a Tasks::toggleSubtask, subtask_id: ' . $subtask_id);
        $subtaskModelo = new SubtaskModel();
        $taskModelo = new TaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $userId = session()->get('user_id');

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para modificar subtareas.');
            return redirect()->to('login');
        }

        $subtask = $subtaskModelo->find($subtask_id);
        if (!$subtask) {
            log_message('error', 'Subtarea no encontrada, id: ' . $subtask_id);
            session()->setFlashdata('error', 'Subtarea no encontrada.');
            return redirect()->to('tasks');
        }

        // Verificar que el usuario es dueño o colaborador
        $tarea = $taskModelo->find($subtask['task_id']);
        $esPropia = $tarea['user_id'] == $userId;
        $esCompartida = $tareaCompartidaModelo->where('task_id', $subtask['task_id'])
                                             ->where('correo_colaborador', session()->get('correo'))
                                             ->first();
        if (!$esPropia && !$esCompartida) {
            log_message('error', 'No tienes permiso para modificar esta subtarea, subtask_id: ' . $subtask_id);
            session()->setFlashdata('error', 'No tienes permiso para modificar esta subtarea.');
            return redirect()->to('tasks');
        }

        // Restricción: Solo el dueño puede modificar subtareas de tareas archivadas
        if ($tarea['is_archived'] == 1 && !$esPropia) {
            log_message('error', 'Los colaboradores no pueden modificar subtareas de tareas archivadas, subtask_id: ' . $subtask_id);
            session()->setFlashdata('error', 'Solo el dueño puede modificar subtareas de tareas archivadas.');
            return redirect()->to('tasks');
        }

        // Cambiar estado de la subtarea
        $data = [
            'is_completed' => !$subtask['is_completed']
        ];

        log_message('debug', 'Estado de subtarea antes de actualizar, subtask_id: ' . $subtask_id . ', is_completed: ' . $subtask['is_completed']);
        log_message('debug', 'Estado de tarea, task_id: ' . $subtask['task_id'] . ', status: ' . $tarea['status']);

        if ($subtaskModelo->update($subtask_id, $data)) {
            // Obtener la subtarea actualizada para confirmar el estado
            $updatedSubtask = $subtaskModelo->find($subtask_id);
            log_message('debug', 'Subtarea actualizada exitosamente, id: ' . $subtask_id . ', is_completed: ' . $updatedSubtask['is_completed']);

            // Si la subtarea se marcó como completada, actualizar tarea a 'in_progress' si no está completada
            if ($updatedSubtask['is_completed'] == 1 && $tarea['status'] != 'completed') {
                $taskData = [
                    'status' => 'in_progress'
                ];
                $taskModelo->update($subtask['task_id'], $taskData);
                $query = $taskModelo->db->getLastQuery();
                log_message('debug', 'Tarea actualizada a in_progress, task_id: ' . $subtask['task_id'] . ', consulta: ' . ($query ? $query->getQuery() : 'No se generó consulta'));
            }

            session()->setFlashdata('exito', 'Subtarea actualizada correctamente.');
        } else {
            log_message('error', 'Error al actualizar subtarea: ' . print_r($subtaskModelo->errors(), true));
            session()->setFlashdata('error', 'Error al actualizar la subtarea.');
        }
        return redirect()->to('tasks');
    }

    public function compartir($task_id)
    {
        log_message('debug', 'Accediendo a Tasks::compartir, task_id: ' . $task_id);
        $modelo = new TaskModel();
        $tareaCompartidaModelo = new TareaCompartidaModel();
        $usuarioModelo = new UsuarioModel();

        $userId = session()->get('user_id');
        log_message('debug', 'User ID en sesión: ' . ($userId ?? 'null'));

        if (!$userId) {
            log_message('error', 'No hay usuario logueado');
            session()->setFlashdata('error', 'Debes iniciar sesión para compartir tareas.');
            return redirect()->to('login');
        }

        $tarea = $modelo->where('user_id', $userId)->find($task_id);
        if (!$tarea) {
            log_message('error', 'Tarea no encontrada o no pertenece al usuario, task_id: ' . $task_id);
            session()->setFlashdata('error', 'Tarea no encontrada o no tienes permiso.');
            return redirect()->to('tasks');
        }

        $data['task'] = $tarea;
        $data['title'] = 'Compartir Tarea';

        if ($this->request->getMethod() === 'get') {
            log_message('debug', 'Mostrando formulario de compartir para task_id: ' . $task_id);
            return view('tasks/compartir', $data);
        }

        // Procesar formulario (POST)
        $correoColaborador = trim($this->request->getPost('correo_colaborador') ?? '');
        log_message('debug', 'Correo colaborador recibido: ' . ($correoColaborador ?: 'vacío'));

        if (!$correoColaborador || !filter_var($correoColaborador, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Correo inválido: ' . ($correoColaborador ?: 'vacío'));
            session()->setFlashdata('error', 'Por favor, ingresa un correo válido.');
            return view('tasks/compartir', $data);
        }

        // Verificar si el colaborador existe en la tabla usuario
        $colaborador = $usuarioModelo->where('correo', $correoColaborador)->first();
        if (!$colaborador) {
            log_message('error', 'Colaborador no encontrado: ' . $correoColaborador);
            session()->setFlashdata('error', 'El correo no corresponde a un usuario registrado.');
            return view('tasks/compartir', $data);
        }

        // Verificar si la tarea ya está compartida con este colaborador
        $yaCompartida = $tareaCompartidaModelo->where('task_id', $task_id)
                                             ->where('correo_colaborador', $correoColaborador)
                                             ->first();
        if ($yaCompartida) {
            log_message('error', 'Tarea ya compartida con: ' . $correoColaborador);
            session()->setFlashdata('error', 'La tarea ya está compartida con este colaborador.');
            return view('tasks/compartir', $data);
        }

        $datos = [
            'task_id' => $task_id,
            'correo_colaborador' => $correoColaborador
        ];

        if ($tareaCompartidaModelo->save($datos)) {
            log_message('debug', 'Tarea compartida exitosamente con: ' . $correoColaborador);
            session()->setFlashdata('exito', 'Tarea compartida correctamente.');
            return redirect()->to('tasks');
        } else {
            log_message('error', 'Error al compartir tarea: ' . print_r($tareaCompartidaModelo->errors(), true));
            session()->setFlashdata('error', 'Error al compartir la tarea.');
            return view('tasks/compartir', $data);
        }
    }
}