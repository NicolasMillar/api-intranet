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
        $rut = $this->request->getPost('rut');
        $password = $this->request->getPost('password');
        $userType = $this->request->getPost('userType');

        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
            echo "<script>console.log('Entro en admin' );</script>";
            $dataUsuario = $admin->obtenerAdmin(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($password, $dataUsuario[0]['Constraseña'])){
                $token = generateToken($dataUsuario[0]['Rut'], $userType);
                return $this->response->setJSON(['token' => $token]);
            }else{
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
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
