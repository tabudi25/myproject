<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDescriptionToCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('categories', 'description');
    }
}

