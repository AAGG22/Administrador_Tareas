
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p>&copy; 2025 Gestor de Tareas. Todos los derechos reservados.</p>
            <div class="social-links">
                <a href="https://twitter.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://github.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://linkedin.com" target="_blank" class="text-white mx-2">
                    <i class="fab fa-linkedin"></i>
                </a>
            </div>
            <p class="mt-2">
                Contacto: <a href="#" class="text-white">alfredodgalvan@gmail.com</a>
            </p>
        </div>
    </footer>
    <!-- Bootstrap 5 JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script>
        // Alternar modo oscuro/claro
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggle = document.querySelector('.theme-toggle');
            const body = document.body;
            const navbar = document.querySelector('.navbar');
            const icon = themeToggle.querySelector('i');

            // Cargar preferencia guardada
            if (localStorage.getItem('theme') === 'light') {
                body.classList.remove('dark-mode');
                body.classList.add('light-mode');
                navbar.classList.add('light-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }

            themeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                body.classList.toggle('light-mode');
                navbar.classList.toggle('light-mode');
                if (body.classList.contains('light-mode')) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                    localStorage.setItem('theme', 'light');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                    localStorage.setItem('theme', 'dark');
                }
            });
        });
    </script>
</body>
</html>