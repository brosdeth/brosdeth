<?php

use App\Model\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            ['id' => 1, 'title' => 'Cash', 'des' => 'Cash',],
            ['id' => 2, 'title' => 'ABA Bank', 'des' => 'ABA Bank',],
            ['id' => 3, 'title' => 'Acleda Bank', 'des' => 'Acleda Bank',],
            ['id' => 4, 'title' => 'Wing', 'des' => 'Wing',],
        ];
        foreach ($methods as $key => $value) {
            PaymentMethod::updateOrCreate(['id' => $value['id']], [
                'title' => $value['title'],
                'des' => $value['des']
            ]);
        }
    }
}
