<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function profile()
    {
        log_message('debug', 'Accediendo a UserController::profile');
        if (!session()->get('logged_in')) {
            log_message('debug', 'Usuario no logueado, redirigiendo a login');
            return redirect()->to('login');
        }
        $data['title'] = 'Perfil';
        $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');
        return view('auth/profile', $data);
    }
}