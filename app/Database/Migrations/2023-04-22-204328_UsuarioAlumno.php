<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UsuarioAlumno extends Migration
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
            'Direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'Comuna' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'Region' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
            'FechaNacimiento' => [
                'type'       => 'Date'
            ],
            'FechaIngreso' => [
                'type'       => 'Date'
            ],
            'Imagen' => [
                'type'       => 'VARCHAR',
                'constraint' => '100'
            ],
        ]);
        $this->forge->addKey('Rut', true);
        $this->forge->createTable('usuarioAlumno');
    }

    public function down()
    {
        $this->forge->dropTable('usuarioAlumno');
    }
}
