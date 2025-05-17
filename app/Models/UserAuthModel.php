<?php
namespace App\Models;
use CodeIgniter\Model;
class UserAuthModel extends Model
{    protected $table      = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false; //con esto no se borra el registro de la base de datos en realidad al hacer un delete
    protected $allowedFields = []; //Nos permite cambiar los campos qeu tengan estos nombre. Si no estan aca no se podrán manipular
    protected $useTimestamps = false;
    protected $deletedField  = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false; // es para indicar que use la validación
}
?>