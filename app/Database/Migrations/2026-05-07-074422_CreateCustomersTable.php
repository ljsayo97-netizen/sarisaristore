<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Create table using raw SQL for better control
        $sql = "CREATE TABLE IF NOT EXISTS `customers` (
            `customer_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) DEFAULT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `address` TEXT DEFAULT NULL,
            `created_at` DATETIME DEFAULT NULL,
            `updated_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`customer_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        $this->db->query($sql);

        // Add missing columns if table already existed but was incomplete
        $fields = ['email', 'phone', 'address', 'created_at', 'updated_at'];
        foreach ($fields as $field) {
            if (!$this->db->fieldExists($field, 'customers')) {
                if ($field == 'address') {
                    $this->db->query("ALTER TABLE `customers` ADD COLUMN `$field` TEXT DEFAULT NULL");
                } elseif ($field == 'created_at' || $field == 'updated_at') {
                    $this->db->query("ALTER TABLE `customers` ADD COLUMN `$field` DATETIME DEFAULT NULL");
                } else {
                    $this->db->query("ALTER TABLE `customers` ADD COLUMN `$field` VARCHAR(255) DEFAULT NULL");
                }
            }
        }

        // Re-enable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('customers');
    }
}
