<?php namespace App\Models;
    use CodeIgniter\Model;

    class usuarioProfesor extends Model{

        public function obtenerProfesor($data) {
            $admin = $this->db->table('usuarioProfesor');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

        public function insertar($data) {
            $databes = $this->db->table('usuarioProfesor');
            $databes->insert($data);
            return $databes->get()->getResultArray();
        }

    }