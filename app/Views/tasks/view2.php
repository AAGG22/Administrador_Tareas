<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>


<div class="container mt-5">
    <h1 class="mb-4"><?= esc($task['title']) ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><?= esc($task['description']) ?></p>
            <p><strong>Prioridad:</strong> <?= translate_priority($task['priority']) ?></p>
            <p><strong>Estado:</strong> <?= translate_status($task['status']) ?></p>
            <?php if ($task['due_date']): ?>
                <p><strong>Fecha límite:</strong> <?= date('d/m/Y', strtotime($task['due_date'])) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <h3 class="mb-3">Subtareas</h3>

    <form method="post" action="<?= site_url("tasks/addSubtask/{$task['id']}") ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="title" class="form-control" placeholder="Nueva subtarea" required>
            <button class="btn btn-primary" type="submit">Añadir</button>
        </div>
    </form>

    <ul class="list-group">
        <?php foreach ($subtasks as $subtask): ?>
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

    <a href="<?= site_url('tasks') ?>" class="btn btn-secondary mt-4">Volver al panel</a>
</div>
<?= $this->endSection() ?>