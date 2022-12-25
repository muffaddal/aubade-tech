<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $factories = [
            [
                'name'    => 'Mobile Phones',
                'parent_id'     => null,
            ],
            [
                'name'    => 'Computers',
                'parent_id'     => null,
            ],
            [
                'name'    => 'Tablets',
                'parent_id'     => null,
            ],
            [
                'name'    => 'Samsung',
                'parent_id'     => 1,
            ],
            [
                'name'    => 'Vivos',
                'parent_id'     => 1,
            ],
        ];

        $builder = $this->db->table('categories');

        foreach ($factories as $factory) {
            $builder->insert($factory);
        }
    }
}
