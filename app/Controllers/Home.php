<?php

namespace App\Controllers;
use App\Models\usuarioAdmin;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login(){
        $rut = $request->getPost('rut');
        $password = $request->getPost('password');
        $userType = $request->getPost('userType');

        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
        }
    }
}
