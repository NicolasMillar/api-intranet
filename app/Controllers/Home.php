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

            $dataUsuario = $admin->obtenerAdmin(['rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($password, $dataUsuario[0]['Constraseña'])){
                $token = generateToken($dataUsuario[0]['Rut'], $userType);
                return $response->setJSON(['token' => $token]);
            }else{
                return $response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
            }
        }
    }

    function generateToken($userId, $userType) {
        $randomString = bin2hex(random_bytes(16));
        $data = ['userId' => $userId, 'userType' => $userType];
        $jsonData = json_encode($data);
        $signature = hash_hmac('sha256', $jsonData, $randomString);
        $token = $randomString . '.' . $signature;
        
        return $token;
    }
}
