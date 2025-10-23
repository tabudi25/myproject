<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
                'null' => false,
                'after' => 'role'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'status');
    }
}

