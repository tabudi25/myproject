<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * @SuppressWarnings(PHPMD)
 */
class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'],
                'default' => 'pending',
                'null' => false,
            ],
            'delivery_type' => [
                'type' => 'ENUM',
                'constraint' => ['pickup', 'delivery'],
                'null' => false,
            ],
            'delivery_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'delivery_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'null' => false,
            ],
            'payment_method' => [
                'type' => 'ENUM',
                'constraint' => ['cod', 'gcash', 'bank_transfer'],
                'default' => 'cod',
                'null' => false,
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed'],
                'default' => 'pending',
                'null' => false,
            ],
            'notes' => [
                'type' => 'TEXT',
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
        $this->forge->addUniqueKey('order_number');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
