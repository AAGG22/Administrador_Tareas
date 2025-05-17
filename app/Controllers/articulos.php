<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class Articulos extends BaseController
{
    function getIndex()
    {
        echo 'Bienvenido a mi primer controlador en CodeIgniter<br><br>';
        return view('listar');
    }
}


