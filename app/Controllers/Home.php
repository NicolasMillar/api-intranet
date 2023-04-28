<?php

namespace App\Controllers;
use App\Models\usuarioAdmin;
use App\Models\usuarioProfesor;
use App\Models\usuarioAlumno;
use Firebase\JWT\JWT;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    private function validateToken($token, $userType){
        try {
            $decodedToken = JWT::decode($token, getenv('JWT_SECRET'), array('HS256'));
            if($userType === 'administrador'){
                $userModel = new usuarioAdmin();
                $user = $userModel->find($decodedToken->user_id);
            }else if($userType === 'profesor'){
                $userModel = new usuarioProfesor();
                $user = $userModel->find($decodedToken->user_id);
            }else{
                $userModel = new usuarioAlumno();
                $user = $userModel->find($decodedToken->user_id);
            }
            if ($user && $decodedToken->exp > time() && $decodedToken->user_id == $user->id) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
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
        $token = $this->request->getHeaderLine('Authorization');
        if (!validateToken($token)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token de seguridad inválido']);
        }
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
            if($response > 0){
                return $this->response->setJSON(['Mensaje' => 'Se creo de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al crear nuevo administrador']);
        }else if($userType === 'profesor'){
            $data = [
                'Rut' => $this->request->getPost('Rut'),
                'Nombre' => $this->request->getPost('Nombre'), 
                'ApellidoM' => $this->request->getPost('ApellidoM'), 
                'ApellidoP' => $this->request->getPost('ApellidoP'), 
                'Constraseña' => $password,
                'Direccion' => $this->request->getPost('Direccion'),
                'Comuna' => $this->request->getPost('Comuna'),
                'Region' => $this->request->getPost('Region'),
                'FechaNacimiento' => $this->request->getPost('FechaNacimiento'),
                'FechaIngreso' => $this->request->getPost('FechaIngreso'),
                'Imagen' => $this->request->getPost('Imagen'),      
            ];
            $profesor = new usuarioProfesor();
            $response = $profesor->insertar($data);
            if($response > 0){
                return $this->response->setJSON(['Mensaje' => 'Se creo de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al crear nuevo profesor']);
        }else{
            $data = [
                'Rut' => $this->request->getPost('Rut'),
                'Nombre' => $this->request->getPost('Nombre'), 
                'ApellidoM' => $this->request->getPost('ApellidoM'), 
                'ApellidoP' => $this->request->getPost('ApellidoP'), 
                'Constraseña' => $password,
                'Direccion' => $this->request->getPost('Direccion'),
                'Comuna' => $this->request->getPost('Comuna'),
                'Region' => $this->request->getPost('Region'),
                'FechaNacimiento' => $this->request->getPost('FechaNacimiento'),
                'FechaIngreso' => $this->request->getPost('FechaIngreso'),
                'Imagen' => $this->request->getPost('Imagen'),      
            ];
            $alumno = new usuarioAlumno();
            $response = $alumno->insertar($data);
            if($response > 0){
                return $this->response->setJSON(['Mensaje' => 'Se creo de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al crear nuevo alumno']);
        }
    }

    public function deleteUser(){
        $token = $this->request->getHeaderLine('Authorization');
        if (!validateToken($token)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token de seguridad inválido']);
        }
        $userType = $this->request->getPost('userType');
        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
            $data = ["Rut" => $this->request->getPost('Rut')];
            $response = $admin->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }else if($userType === 'profesor'){
            $profesor = new usuarioProfesor();
            $data = ["Rut" => $this->request->getPost('Rut')];
            $response = $profesor->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }else{
            $alumno = new usuarioAlumno();
            $data = ["Rut" => $this->request->getPost('Rut')];
            $response = $alumno->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }
    }

    public function test(){
        $token = $this->request->getHeaderLine('Authorization');
        if(!$this->validateToken($token)){
            return $this->response->setJSON(['token' => $token]);
        }
        return $this->response->setJSON(['Mensaje' => 'token invalido' ]);
    }

}
