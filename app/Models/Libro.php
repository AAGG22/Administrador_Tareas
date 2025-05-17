<?php
namespace App\Models;
use CodeIgniter\Model;
class Libro extends Model
{
    protected $DBGroup = 'biblioteca'; // Conexión a la base de datos
    protected $table = 'libros';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'imagen']; //activar el acceso a las columnas

}