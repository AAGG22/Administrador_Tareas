<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UsuarioModel;

class Auth extends Controller
{
    public function login()
    {
        log_message('debug', 'Accediendo a Auth::login');
        if (session()->get('user_id')) {
            log_message('debug', 'Usuario ya logueado, redirigiendo al Panel de tareas');
            return redirect()->to('tasks');
        }
        $data['title'] = 'Iniciar Sesión';
        $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');
        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        log_message('debug', 'Accediendo a Auth::attemptLogin');
        $model = new UsuarioModel();
        $correo = trim($this->request->getPost('correo'));
        $password = $this->request->getPost('password');

        log_message('debug', 'Datos recibidos: correo=' . ($correo ?? 'null') . ', password=' . (empty($password) ? 'empty' : 'provided'));

        if (!$correo || !$password) {
            log_message('error', 'Correo o contraseña vacíos');
            session()->setFlashdata('error', 'Por favor, ingresa correo y contraseña.');
            return redirect()->back();
        }

        $user = $model->where('correo', $correo)->first();
        log_message('debug', 'Resultado de la consulta: user=' . ($user ? json_encode($user) : 'null'));

        if (!$user) {
            log_message('error', 'Usuario no encontrado: ' . $correo);
            session()->setFlashdata('error', 'Correo o contraseña incorrectos.');
            return redirect()->back();
        }

        if (!isset($user['contrasena'])) {
            log_message('error', 'Columna contrasena no encontrada en usuario: ' . json_encode($user));
            session()->setFlashdata('error', 'Error en la configuración del usuario.');
            return redirect()->back();
        }

        if (!password_verify($password, $user['contrasena'])) {
            log_message('error', 'Contraseña incorrecta para: ' . $correo);
            session()->setFlashdata('error', 'Correo o contraseña incorrectos.');
            return redirect()->back();
        }

        session()->set([
            'user_id' => $user['id'], // Cambiado de id_usuario a id
            'correo' => $user['correo'],
            'logged_in' => true
        ]);
        log_message('debug', 'Inicio de sesión exitoso para: ' . $correo);
        log_message('debug', 'Redirigiendo a: Panel de Tareas (URL completa: ' . site_url('tasks') . ')');
        session()->setFlashdata('exito', 'Bienvenido, ' . $user['correo'] . '!');
        return redirect()->to('tasks');
    }

    public function logout()
    {
        log_message('debug', 'Accediendo a Auth::logout');
        session()->destroy();
        return redirect()->to('login');
    }
}