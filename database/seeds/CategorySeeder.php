<?php

use App\Model\Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = [
            ["id" => 1, "cat_name" => "ទឹកដោះគោ"],

        ];

        foreach ($categories as $value) {
            Categories::updateOrCreate(['id' => $value['id']], [
                'cat_name' => $value['cat_name'],
                'desc' => $value['cat_name'],
            ]);
        }
    }
}
