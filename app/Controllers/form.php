<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Form extends BaseController
{

    public function __construct()
    {

        helper('form');
    }

    public function getIndex()
    {
        return view('myform');
    }

    public function postExito()
    {

        $validation = service('validation');
        $validation->setRules(
            [

                'nombre' => 'required|min_length[3]|max_length[12]|alpha_numeric_space',
                'password' => 'required|min_length[8]|matches[c_password]',
                'c_password' => 'required',
                'email' => 'required|valid_email',

            ],
            [
                'nombre' => [
                    'required' => 'El campo nombre es obligatorio',
                    'min_length' => 'La longitud minima es 3',
                    'max_length' => 'La longitud maxima es 12',
                    'alpha_numeric_space' => 'Solo puede ser caracteres alfanumericos y el espacio'
                ],
                'password' => [
                    'required' => 'El campo password es obligatorio',
                    'min_length' => 'La longitud minima es 8',
                    'matches[c_password]' => 'debe ser igual a c-password'
                ]
            ]

        );

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = array(
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        );
        return view('formsuccess', $data);
    }
}
