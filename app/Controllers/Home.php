<?php

namespace App\Controllers;
use App\Models\usuarioAdmin;
use App\Models\usuarioProfesor;
use App\Models\usuarioAlumno;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function login(){

        function generateToken($userId, $userType) {
            $randomString = bin2hex(random_bytes(16));
            $data = ['userId' => $userId, 'userType' => $userType];
            $jsonData = json_encode($data);
            $signature = hash_hmac('sha256', $jsonData, $randomString);
            $token = $randomString . '.' . $signature;
            
            return $token;
        }

        $rut = $this->request->getPost('rut');
        $passowrd = $this->request->getPost('password');
        $userType = $this->request->getPost('userType');
        
        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
            $dataUsuario = $admin->obtenerAdmin(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = generateToken($dataUsuario[0]['Rut'], $userType);

                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }else if($userType === 'profesor'){
            $profesor = new usuarioProfesor();
            $dataUsuario = $profesor->obtenerProfesor(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = generateToken($dataUsuario[0]['Rut'], $userType);
                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }else if($userType === 'alumno'){
            $alumno = new usuarioAlumno();
            $dataUsuario = $alumno->obtenerAlumno(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = generateToken($dataUsuario[0]['Rut'], $userType);
                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }

        return $this->response->setStatusCode(401)->setJSON(['error' => 'Debe seleccionar un Tipo de usuario']);
    }

    public function createUser(){
        $userType = $this->request->getPost('userType');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        if($userType === 'administrador'){
            $data = [
                'Rut' => $this->request->getPost('Rut'),
                'Nombre' => $this->request->getPost('Nombre'), 
                'ApellidoM' => $this->request->getPost('ApellidoM'), 
                'ApellidoP' => $this->request->getPost('ApellidoP'), 
                'Constraseña' => $password
            ];
            $admin = new usuarioAdmin();
            $response = $admin->insertar($data);
            if($respuesta > 0){
                return $this->response->setJSON(['Mensaje' => 'Se creo de forma correcta' ]);
            }else{
                return $this->response->setStatusCode(401)->setJSON(['error' => 'error al crear nuevo administrador']);
            }
        }else if($userType === 'profesor'){

        }else{

        }
    }

    public function deleteUser(){
        //logica para eliminar usuarios
    }
    
}
