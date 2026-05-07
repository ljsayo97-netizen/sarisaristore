<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LinkUtangToSales extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        if (!$this->db->fieldExists('sale_id', 'utang')) {
            $this->forge->addColumn('utang', [
                'sale_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'customer_id'
                ]
            ]);
        }

        // Make product_id nullable since multiple products will be in sale_items linked via sale_id
        $this->db->query("ALTER TABLE utang MODIFY COLUMN product_id INT(11) UNSIGNED NULL");

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
    }
}
