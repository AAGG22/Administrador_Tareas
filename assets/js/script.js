document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de Gestión de Tareas cargado');
    
    // Cerrar mensajes automáticamente después de 5 segundos
    setTimeout(() => {
        const mensajes = document.querySelectorAll('.mensaje, .error');
        mensajes.forEach(msg => {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        });
    }, 5000);
});