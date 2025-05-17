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
    
    <a href="<?= site_url('tasks') ?>" class="btn btn-secondary mb-3">Volver a Tareas</a>
    
    <!-- Lista de tareas archivadas -->
    <?php if (empty($tasks)): ?>
        <p>No hay tareas archivadas para mostrar.</p>
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
                                            <?= esc($subtask['title']) ?> (<?= $subtask['is_completed'] ? 'Completada' : 'Pendiente' ?>)
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php echo $footer; ?>