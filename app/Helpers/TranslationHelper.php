<?php

if (!function_exists('translate_priority')) {
    function translate_priority($priority)
    {
        $translations = [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta'
        ];
        return $translations[$priority] ?? $priority;
    }
}

if (!function_exists('translate_status')) {
    function translate_status($status)
    {
        $translations = [
            'pending' => 'Pendiente',
            'in_progress' => 'En progreso',
            'completed' => 'Completada'
        ];
        return $translations[$status] ?? $status;
    }
}