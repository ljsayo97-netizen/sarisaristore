<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUtangTable extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Check if table exists
        if (!$this->db->tableExists('utang')) {
            $this->forge->addField([
                'utang_id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'customer_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'product_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'amount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['paid', 'unpaid'],
                    'default'    => 'unpaid',
                ],
                'date' => [
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
            $this->forge->addKey('utang_id', true);
            $this->forge->createTable('utang');
        } else {
            // Table exists, adjust columns to match request
            if (!$this->db->fieldExists('product_id', 'utang')) {
                $this->forge->addColumn('utang', [
                    'product_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'after'      => 'customer_id'
                    ]
                ]);
            }
            
            // Adjust status to simple paid/unpaid if needed
            $this->db->query("ALTER TABLE utang MODIFY COLUMN status ENUM('paid', 'unpaid') DEFAULT 'unpaid'");
            
            if (!$this->db->fieldExists('created_at', 'utang')) {
                $this->forge->addColumn('utang', [
                    'created_at' => ['type' => 'DATETIME', 'null' => true],
                    'updated_at' => ['type' => 'DATETIME', 'null' => true],
                ]);
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No down migration for safety of existing data
    }
}
