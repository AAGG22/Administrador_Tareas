<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Gestor de Tareas') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .dark-mode {
            background-color: #222;
            color: #fff;
        }
        .light-mode {
            background-color: #fff;
            color: #000;
        }
        .navbar.light-mode {
            background-color: #f8f9fa !important;
        }
    </style>
</head>
<body class="dark-mode">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url() ?>">Gestor de Tareas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->get('user_id')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('tasks') ?>">Panel de Tareas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('tasks/archived') ?>">Tareas Archivadas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('tasks/create') ?>">Crear Tarea</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('logout') ?>">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('login') ?>">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('register') ?>">Registrarse</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link theme-toggle" href="#"><i class="fas fa-moon"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">