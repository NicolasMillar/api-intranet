<?php namespace App\Models;
    use Codeigniter\Model;

    class usuarioAdmin extends Model{

        public function obtenerAdmin($data) {
            $admin = $this->db->table('usuarioAdmin');
            $admin->where($data);
            return $admin->get()->getResultArray();
        }

    }