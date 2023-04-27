<?php namespace App\Models;
    use CodeIgniter\Model;

    class usuarioAlumno extends Model{

        public function obtenerAlumno($data) {
            $admin = $this->db->table('usuarioAlumno');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

        public function insertar($data) {
            $databes = $this->db->table('usuarioAlumno');
            $databes->insert($data);
            return $databes->get()->getResultArray();
        }

        public function eliminar($data){
            $database = $this->db->table('usuarioAlumno');
            $database->where($data);
            return $database->delete();
        }
    }