<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHasilPerhitungan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'periode' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
            ],
            'salesman_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nilai_preferensi' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,8',
                'default'    => 0,
            ],
            'ranking' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'd_plus' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,8',
                'default'    => 0,
            ],
            'd_minus' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,8',
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['periode', 'salesman_id']);
        $this->forge->createTable('hasil_perhitungan');
    }

    public function down()
    {
        $this->forge->dropTable('hasil_perhitungan');
    }
}