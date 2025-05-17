<?php 
namespace App\Controllers;
use Config\Services;
use CodeIgniter\Controller;
use App\Models\UserAuthModel;

class Navegar extends BaseController{
    public function __construct(){
        helper('form');
        $session = \Config\Services::session();
    }

    public function getIndex (){
        return view('login');
    }

    public function postIniciar(){

        //$userModel = new UserAuthModel();
        $db = \Config\Database::connect('ejemplo_sesiones'); // Conectar a la BD específica
        $userModel = new UserAuthModel($db); // Pasar la conexión al modelo
        $request = \Config\Services::request();

        if(($u=$userModel->where('nombre',$request->getPostGet('user'))->findAll()) &&
            ($u[0]['password']==$request->getPostGet('pass')))
            {
                $session = \Config\Services::session();
                if (!isset($_SESSION['navegar'])) 
            {
                $session->set('user',$request->getPostGet('user'));
                $session->set('navegar',0);
            }

            return view ('principal');
            }else{
                $error=[
                    'error'=>TRUE,
                    'user'=>$request->getPostGet('user'),
                    'pass'=>$request->getPostGet('pass')
                ];
                return view ('login',$error);
            }

    }

    public function getPagina1(){
        return view ('pagina1');
    }

    public function getPagina2(){
        return view ('pagina2');
    }

    public function getPrincipal(){
        return view ('principal');
    }

    public function getCerrar()
    {
        session_destroy();
        return view ('login');
    }

}



?>