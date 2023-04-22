<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsuarioAdmin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Rut' => [
                'type'           => 'Varchar',
                'constraint'     => '19'
            ],
            'Nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'ApellidoM' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'ApellidoP' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'Constraseña' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
        ]);
        $this->forge->addKey('Rut', true);
        $this->forge->createTable('usuarioAdmin');
    }

    public function down()
    {
        $this->forge->dropTable('usuarioAdmin');
    }
}
