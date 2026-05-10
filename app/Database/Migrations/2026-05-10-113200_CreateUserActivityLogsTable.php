<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserActivityLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'log_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['login', 'logout'],
            ],
            'timestamp' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('log_id', true);
        $this->forge->createTable('user_activity_logs');
    }

    public function down()
    {
        $this->forge->dropTable('user_activity_logs');
    }
}
