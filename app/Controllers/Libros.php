<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Libro;

class Libros extends Controller
{
    public function index()
    {
        $libro = new Libro();
        $datos['libros'] = $libro->orderBy('id', 'ASC')->findAll();

  // Depuración: ver qué datos se están enviando
  //  echo '<pre>';
 //   print_r($datos['libros']);
 //   echo '</pre>';
  //  die();


        return view('listar', $datos);

    }
    
}
