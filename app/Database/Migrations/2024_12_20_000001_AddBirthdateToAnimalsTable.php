<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBirthdateToAnimalsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('animals', [
            'birthdate' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'gender'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('animals', 'birthdate');
    }
}

