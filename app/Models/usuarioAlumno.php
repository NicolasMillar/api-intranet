<?php namespace App\Models;
    use CodeIgniter\Model;

    class usuarioAlumno extends Model{

        public function obtenerAlumno($data) {
            $admin = $this->db->table('usuarioAlumno');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

    }