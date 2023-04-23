<?php namespace App\Models;
    use CodeIgniter\Model;

    class usuarioAdmin extends Model{

        public function obtenerAdmin($data) {
            $admin = $this->db->table('usuarioAdmin');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

    }