<?php echo $cabecera; ?>

<style>
    .subtask-completed {
        text-decoration: line-through;
        color: #6c757d;
    }
</style>

<div class="container mt-5">
    <h1 class="mb-4"><?= esc($title) ?></h1>
    
    <!-- Mostrar correo del usuario logueado -->
    <p><strong>Usuario logueado:</strong> <?= esc($correo_logueado ?: 'No logueado') ?></p>
    
    <!-- Mensajes de éxito o error -->
    <?php if (session()->getFlashdata('exito')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('exito') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <a href="<?= site_url('tasks/create') ?>" class="btn btn-primary mb-3">Crear Nueva Tarea</a>
    <a href="<?= site_url('tasks/archived') ?>" class="btn btn-secondary mb-3">Ver Tareas Archivadas</a>
    
    <!-- Opciones de ordenamiento -->
    <form method="get" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="sort_by" class="form-control">
                    <option value="title" <?= $sort_by === 'title' ? 'selected' : '' ?>>Título</option>
                    <option value="priority" <?= $sort_by === 'priority' ? 'selected' : '' ?>>Prioridad</option>
                    <option value="status" <?= $sort_by === 'status' ? 'selected' : '' ?>>Estado</option>
                    <option value="due_date" <?= $sort_by === 'due_date' ? 'selected' : '' ?>>Fecha de Vencimiento</option>
                    <option value="created_at" <?= $sort_by === 'created_at' ? 'selected' : '' ?>>Fecha de Creación</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sort_order" class="form-control">
                    <option value="asc" <?= $sort_order === 'asc' ? 'selected' : '' ?>>Ascendente</option>
                    <option value="desc" <?= $sort_order === 'desc' ? 'selected' : '' ?>>Descendente</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary">Ordenar</button>
            </div>
        </div>
    </form>
    
    <!-- Lista de tareas -->
    <?php if (empty($tasks)): ?>
        <p>No hay tareas para mostrar.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Tipo</th>
                    <th>Dueño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= esc($task['title']) ?></td>
                        <td><?= esc(translate_priority($task['priority'])) ?></td>
                        <td><?= esc(translate_status($task['status'])) ?></td>
                        <td><?= $task['due_date'] ? esc($task['due_date']) : 'Sin fecha' ?></td>
                        <td><?= $task['es_propia'] ? 'Propia' : 'Compartida' ?></td>
                        <td><?= esc($task['correo_dueno']) ?></td>
                        <td>
                            <a href="<?= site_url('tasks/view/' . $task['id']) ?>" class="btn btn-info btn-sm">Ver</a>
                            <a href="<?= site_url('tasks/edit/' . $task['id']) ?>" class="btn btn-warning btn-sm">Editar</a>
                            <?php if ($task['es_propia']): ?>
                                <a href="<?= site_url('tasks/delete/' . $task['id']) ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar</a>
                                <a href="<?= site_url('tasks/compartir/' . $task['id']) ?>" class="btn btn-secondary btn-sm">Compartir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- Mostrar subtareas -->
                    <?php if (!empty($task['subtasks'])): ?>
                        <tr>
                            <td colspan="7">
                                <strong>Subtareas:</strong>
                                <ul>
                                    <?php foreach ($task['subtasks'] as $subtask): ?>
                                        <li class="<?= $subtask['is_completed'] ? 'subtask-completed' : '' ?>">
                                            <form action="<?= site_url('tasks/toggleSubtask/' . $subtask['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="checkbox" name="is_completed" 
                                                       onchange="this.form.submit()" 
                                                       <?= $subtask['is_completed'] ? 'checked' : '' ?>>
                                            </form>
                                            <?= esc($subtask['title']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <!-- Formulario para añadir subtarea (solo para tareas propias) -->
                    <?php if ($task['es_propia']): ?>
                        <tr>
                            <td colspan="7">
                                <form action="<?= site_url('tasks/addSubtask/' . $task['id']) ?>" method="post" class="d-flex">
                                    <?= csrf_field() ?>
                                    <input type="text" class="form-control me-2" name="subtask_title" placeholder="Nueva subtarea" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Añadir Subtarea</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php echo $footer; ?>