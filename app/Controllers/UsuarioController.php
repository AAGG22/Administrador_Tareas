<?php namespace App\Controllers;

use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    protected $modeloUsuario;

    public function __construct()
    {
        $this->modeloUsuario = new UsuarioModel();
        helper(['form', 'url']);
    }

    public function registro()
    {
        if ($this->request->getMethod() === 'post') {
            $reglas = [
                'nombre_usuario' => 'required|min_length[3]|max_length[50]|is_unique[Usuarios.nombre_usuario]',
                'correo' => 'required|valid_email|max_length[100]|is_unique[Usuarios.correo]',
                'contraseña' => 'required|min_length[8]',
                'confirmar_contraseña' => 'required|matches[contraseña]',
                'nombre_completo' => 'permit_empty|max_length[100]'
            ];

            if ($this->validate($reglas)) {
                $datosUsuario = [
                    'nombre_usuario' => $this->request->getPost('nombre_usuario'),
                    'correo' => $this->request->getPost('correo'),
                    'contraseña_hash' => $this->request->getPost('contraseña'),
                    'nombre_completo' => $this->request->getPost('nombre_completo'),
                    'esta_activo' => true
                ];

                $this->modeloUsuario->insert($datosUsuario);
                return redirect()->to(base_url('login'))->with('mensaje', 'Registro exitoso. Por favor inicia sesión.');
            } else {
                return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
            }
        }

        return view('usuario/layout', ['contenido' => view('usuario/registro')]);
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $reglas = [
                'correo' => 'required|valid_email',
                'contraseña' => 'required'
            ];

            if ($this->validate($reglas)) {
                $correo = $this->request->getPost('correo');
                $contraseña = $this->request->getPost('contraseña');

                $usuario = $this->modeloUsuario->verificarCredenciales($correo, $contraseña);

                if ($usuario) {
                    $sesion = session();
                    $sesion->set([
                        'id_usuario' => $usuario['id_usuario'],
                        'nombre_usuario' => $usuario['nombre_usuario'],
                        'correo' => $usuario['correo'],
                        'esta_logueado' => true
                    ]);

                    // Actualizar último acceso
                    $this->modeloUsuario->update($usuario['id_usuario'], ['ultimo_acceso' => date('Y-m-d H:i:s')]);

                    return redirect()->to(base_url('tareas'))->with('mensaje', 'Bienvenido ' . $usuario['nombre_usuario']);
                } else {
                    return redirect()->back()->withInput()->with('error', 'Credenciales incorrectas');
                }
            } else {
                return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
            }
        }

        return view('usuario/layout', ['contenido' => view('usuario/login')]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('mensaje', 'Has cerrado sesión correctamente');
    }
}