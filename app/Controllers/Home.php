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

    private function generateToken($userId, $userType) {
        $randomString = getenv('JWT_SECRET');
        $data = ['userId' => $userId, 'userType' => $userType];
        $jsonData = json_encode($data);
        $signature = hash_hmac('sha256', $jsonData, $randomString);
        $token = $randomString . '.' . $signature;
        
        return $token;
    }

    private function validateToken($token, $userId, $userType){
        $newtoken = $this->generateToken($userId, $userType);

        $longitudC1 = strlen($newtoken);
        $longitudC2 = strlen($token);
        $LongitudMin = min($longitudC1, $longitudC2);
        $similitudMinima = $LongitudMin*0.27;
        $caracteresIguales = 0;

        for($i=0; $i<$LongitudMin; $i++){
            if($newtoken[$i] === $token[$i]){
                $caracteresIguales++;
            }
        }

        if($caracteresIguales >= $similitudMinima){
            return true;
        }else{
            return false;
        }
    }

    public function login(){

        $rut = $this->request->getPost('rut');
        $passowrd = $this->request->getPost('password');
        $userType = $this->request->getPost('userType');
        
        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
            $dataUsuario = $admin->obtenerAdmin(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = $this->generateToken($dataUsuario[0]['Rut'], $userType);

                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }else if($userType === 'profesor'){
            $profesor = new usuarioProfesor();
            $dataUsuario = $profesor->obtenerProfesor(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = $this->generateToken($dataUsuario[0]['Rut'], $userType);
                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }else if($userType === 'alumno'){
            $alumno = new usuarioAlumno();
            $dataUsuario = $alumno->obtenerAlumno(['Rut' => $rut]);

            if(count($dataUsuario) > 0 && password_verify($passowrd, $dataUsuario[0]['Constraseña'])){
                $token = $this->generateToken($dataUsuario[0]['Rut'], $userType);
                return $this->response->setJSON(['token' => $token, 'user' => $dataUsuario[0]]);
            }

            return $this->response->setStatusCode(401)->setJSON(['error' => 'Credenciales inválidas']);
        }

        return $this->response->setStatusCode(401)->setJSON(['error' => 'Debe seleccionar un Tipo de usuario']);
    }

    public function createUser(){
        $token = $this->request->getHeaderLine('Authorization');
        $userType = $this->request->getPost('userType');
        $Rut = $this->request->getPost('Rut');
        if (!$this->validateToken($token, $Rut, $userType)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token de seguridad inválido']);
        }
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        if($userType === 'administrador'){
            $data = [
                'Rut' => $Rut,
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
                'Rut' => $Rut,
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
                'Rut' => $Rut,
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
        $userType = $this->request->getPost('userType');
        $Rut = $this->request->getPost('Rut');
        if (!$this->validateToken($token, $Rut, $userType)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token de seguridad inválido']);
        }
        if($userType === 'administrador'){
            $admin = new usuarioAdmin();
            $data = ["Rut" => $Rut];
            $response = $admin->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }else if($userType === 'profesor'){
            $profesor = new usuarioProfesor();
            $data = ["Rut" => $Rut];
            $response = $profesor->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }else{
            $alumno = new usuarioAlumno();
            $data = ["Rut" => $Rut];
            $response = $alumno->eliminar($data);
            if($response){
                return $this->response->setJSON(['Mensaje' => 'Se elimino de forma correcta' ]);
            }
            return $this->response->setStatusCode(401)->setJSON(['error' => 'error al eliminar administrador']);
        }
    }

    public function test(){
        $token = $this->request->getHeaderLine('Authorization');
        $Rut = $this->request->getPost('Rut');
        $userType = $this->request->getPost('userType');
        if(!$this->validateToken($token, $Rut, $userType)){
            return $this->response->setJSON(['Mensaje' => 'Token invalido' ]);
        }
        return $this->response->setJSON(['Mensaje' => 'Token valido' ]);
    }

}
