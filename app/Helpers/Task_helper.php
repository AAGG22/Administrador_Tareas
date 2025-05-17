<?php // ¡Este tag de apertura PHP es esencial!

if (!function_exists('translate_status')) {
    function translate_status($status)
    {
        $statuses = [
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado'
        ];
        return $statuses[strtolower($status)] ?? $status;
    }
}

if (!function_exists('translate_priority')) {
    function translate_priority($priority)
    {
        $priorities = [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta'
        ];
        return $priorities[strtolower($priority)] ?? $priority;
    }
}