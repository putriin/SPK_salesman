<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerhitunganSnapshot extends Migration
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
            'source_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'snapshot_json' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'calculated_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addUniqueKey('periode');
        $this->forge->createTable('perhitungan_snapshot');
    }

    public function down()
    {
        $this->forge->dropTable('perhitungan_snapshot');
    }
}