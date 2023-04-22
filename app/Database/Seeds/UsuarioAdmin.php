<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioAdmin extends Seeder
{
    public function run()
    {
        $password = password_hash(env('SEED_PASSWORD'), PASSWORD_DEFAULT);

        $data = [
            'Rut' => '198347442',
            'Nombre' => 'Nicolás Alejandro',
            'ApellidoM' => 'Millar',
            'ApellidoP' => 'Millar',
            'Constraseña' => $password
        ];

        $this->db->table('usuarioAdmin')->insert($data);
    }
}
