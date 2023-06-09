<?php namespace App\Models;
    use CodeIgniter\Model;

    class usuarioAdmin extends Model{

        public function obtenerAdmin($data) {
            $admin = $this->db->table('usuarioAdmin');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

        public function insertar($data) {
            $databes = $this->db->table('usuarioAdmin');
            $databes->insert($data);
            return $databes->get()->getResultArray();
        }

        public function eliminar($data){
            $database = $this->db->table('usuarioAdmin');
            $database->where($data);
            return $database->delete();
        }

        public function econtrar($data){
            $database = $this->db->table('usuarioAdmin');
            $database->where($data);
            return $database->get()->getResultArray();
        }

    }