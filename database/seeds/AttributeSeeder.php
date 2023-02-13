<?php

use App\Model\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = [
            [
                'id' => 1,
                'attr_name' => 'Size',
                'description' => 'Size',
            ],
            [
                'id' => 2,
                'attr_name' => 'Color',
                'description' => 'Color',
            ],
            [
                'id' => 3,
                'attr_name' => 'Capacity',
                'description' => 'Capacity',
            ],
            [
                'id' => 4,
                'attr_name' => 'Weight',
                'description' => 'Weight',
            ],

        ];
        foreach ($attributes as $value) {
            Attribute::updateOrCreate(['id' => $value['id']], [
                'attr_name' => $value['attr_name'],
                'description' => $value['description'],
            ]);
        }
    }
}
