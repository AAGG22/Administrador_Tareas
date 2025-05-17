<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= $title ?></h1>
    
    <a href="<?= site_url('tasks/create') ?>" class="btn btn-primary mb-3">Nueva Tarea</a>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <div class="accordion" id="tasksAccordion">
        <?php foreach ($tasks as $index => $task): ?>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header" id="heading<?= $task['id'] ?>">
                    <button class="accordion-button accordion-toggle <?= $index === 0 ? '' : 'collapsed' ?>" 
                            type="button" 
                            data-bs-target="#collapse<?= $task['id'] ?>" 
                            aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                            aria-controls="collapse<?= $task['id'] ?>">
                        <div class="d-flex justify-content-between w-100 align-items-center">
                            <div>
                                <strong><?= esc($task['title']) ?></strong>
                                <div class="mt-1">
                                    <span class="badge bg-<?= 
                                        $task['priority'] === 'high' ? 'danger' : 
                                        ($task['priority'] === 'medium' ? 'warning' : 'success') 
                                    ?>">
                                        <?= translate_priority($task['priority']) ?>
                                    </span>
                                    <span class="badge bg-<?= 
                                        $task['status'] === 'completed' ? 'success' : 
                                        ($task['status'] === 'in_progress' ? 'primary' : 'secondary') 
                                    ?> ms-2">
                                        <?= translate_status($task['status']) ?>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <a href="<?= site_url("tasks/view/{$task['id']}") ?>" class="btn btn-sm btn-outline-primary me-2">Ver Detalles</a>
                                <a href="<?= site_url("tasks/delete/{$task['id']}") ?>" 
                                   class="btn btn-sm btn-outline-danger <?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'disabled' : '' ?>"
                                   onclick="<?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'alert(\'No se puede eliminar: tiene subtareas pendientes\'); return false;' : 'return confirm(\'¿Estás seguro de eliminar esta tarea?\')' ?>"
                                   <?= isset($task['has_incomplete_subtasks']) && $task['has_incomplete_subtasks'] ? 'title="No se puede eliminar: tiene subtareas pendientes"' : '' ?>>
                                   Eliminar
                                </a>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse<?= $task['id'] ?>" 
                     class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                     aria-labelledby="heading<?= $task['id'] ?>" 
                     data-bs-parent="#tasksAccordion">
                    <div class="accordion-body">
                        <h5>Subtareas</h5>
                        <form method="post" action="<?= site_url("tasks/addSubtask/{$task['id']}") ?>" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="title" class="form-control" placeholder="Nueva subtarea" required>
                                <button class="btn btn-primary" type="submit">Añadir</button>
                            </div>
                        </form>
                        <?php if (isset($task['subtasks']) && !empty($task['subtasks'])): ?>
                            <ul class="list-group">
                                <?php foreach ($task['subtasks'] as $subtask): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <form method="post" action="<?= site_url("tasks/toggleSubtask/{$subtask['id']}") ?>" class="d-inline">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           <?= $subtask['is_completed'] ? 'checked' : '' ?>
                                                           onchange="this.form.submit()">
                                                </div>
                                            </form>
                                            <span class="ms-2 <?= $subtask['is_completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                                <?= esc($subtask['title']) ?>
                                            </span>
                                        </div>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($subtask['created_at'])) ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No hay subtareas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>