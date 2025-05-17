<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Home extends Controller
{
    public function index()
    {
        log_message('debug', 'Accediendo a Home::index');
        $data['title'] = 'Inicio';
        $data['cabecera'] = view('templates/cabecera');
        $data['footer'] = view('templates/footer');
        return view('auth/login', $data);
    }
}