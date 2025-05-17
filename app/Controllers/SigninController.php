<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class SigninController extends Controller
{
    public function index()
    {
        log_message('debug', 'Accediendo a SigninController::index');
        helper(['form']);
        $data['title'] = 'Iniciar Sesión';
        return view('signin/index', $data);
    }

    public function loginAuth()
    {
        log_message('debug', 'Accediendo a SigninController::loginAuth');
        $correo = $this->request->getPost('correo');
        $password = $this->request->getPost('password');

        log_message('debug', 'Datos recibidos: correo=' . $correo);

        $usuarioModelo = new UsuarioModel();
        $usuario = $usuarioModelo->where('correo', $correo)->first();

        if ($usuario && password_verify($password, $usuario['contraseña_hash'])) {
            log_message('debug', 'Usuario encontrado: ' . $correo);
            session()->set([
                'user_id' => $usuario['id_usuario'],
                'correo' => $usuario['correo'],
                'logged_in' => true
            ]);
            log_message('debug', 'Login exitoso para: ' . $correo);
            return redirect()->to('profile');
        } else {
            log_message('error', 'Credenciales inválidas para: ' . $correo);
            session()->setFlashdata('error', 'Correo o contraseña incorrectos.');
            return redirect()->to('login');
        }
    }

    public function logout()
    {
        log_message('debug', 'Accediendo a SigninController::logout');
        session()->destroy();
        return redirect()->to('login');
    }
}