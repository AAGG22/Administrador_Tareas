<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UsuarioModel;

class SignupController extends Controller
{
    public function index()
    {
        log_message('debug', 'Accediendo a SignupController::index');
        return view('auth/register');
    }

    public function store()
    {
        log_message('debug', 'Accediendo a SignupController::store');
        // Forzar la conexión a gestor_tareas
        $db = \Config\Database::connect('gestor_tareas');
        $userModel = new UsuarioModel($db);
        
        // Depurar método y datos recibidos
        $method = $this->request->getMethod();
        log_message('debug', 'Método HTTP detectado: ' . $method);
        log_message('debug', 'Datos recibidos: ' . print_r($this->request->getPost(), true));
        
        if ($this->request->getMethod(true) === 'POST') {
            log_message('debug', 'Datos POST confirmados');
            
            $rules = [
                'nombre' => 'required|min_length[2]|max_length[50]',
                'apellido' => 'required|min_length[2]|max_length[50]',
                'correo' => 'required|valid_email',
                'contrasena' => 'required|min_length[8]'
            ];
            
            $validation = \Config\Services::validation();
            $validation->setRules($rules);
            
            // Validar is_unique manualmente
            $correo = $this->request->getPost('correo');
            $existingUser = $db->table('usuario')->where('correo', $correo)->get()->getRow();
            if ($existingUser) {
                $validation->setError('correo', 'El correo ya está registrado.');
            }
            
            if ($validation->withRequest($this->request)->run()) {
                $data = [
                    'nombre' => $this->request->getPost('nombre'),
                    'apellido' => $this->request->getPost('apellido'),
                    'correo' => $correo,
                    'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT)
                ];
                
                if ($userModel->save($data)) {
                    log_message('debug', 'Usuario guardado exitosamente: ' . $data['correo']);
                    session()->setFlashdata('success', 'Registro exitoso. Por favor, inicia sesión.');
                    return redirect()->to('login');
                } else {
                    log_message('error', 'Error al guardar usuario: ' . print_r($userModel->errors(), true));
                    return redirect()->to('register')->withInput()->with('errors', $userModel->errors());
                }
            } else {
                log_message('error', 'Errores de validación: ' . print_r($validation->getErrors(), true));
                return redirect()->to('register')->withInput()->with('errors', $validation->getErrors());
            }
        } else {
            log_message('error', 'Método no es POST, detectado: ' . $method);
            session()->setFlashdata('error', 'Error: El formulario debe enviarse mediante POST.');
            return redirect()->to('register');
        }
    }
}