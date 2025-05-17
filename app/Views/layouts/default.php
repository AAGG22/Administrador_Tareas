<?php
// CodeIgniter 4 default layout
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('css/styles.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <!-- Content from views -->
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for accordion -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle accordion
            document.querySelectorAll('.accordion-toggle').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-bs-target');
                    const target = document.querySelector(targetId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    target.classList.toggle('show');
                });
            });
        });
    </script>
</body>
</html>