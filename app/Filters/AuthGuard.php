<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message('debug', 'Ejecutando AuthGuard::before');
        if (!session()->get('logged_in')) {
            log_message('debug', 'Usuario no logueado, redirigiendo a login');
            return redirect()->to('login');
        }
        log_message('debug', 'Usuario autenticado, permitiendo acceso');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada
    }
}