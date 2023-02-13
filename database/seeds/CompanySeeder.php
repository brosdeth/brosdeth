<?php

use App\Model\CompanyInfor;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyInfor::updateOrCreate(['id' => 1], [
            'name_en' => 'POS',
            'name_km' => 'NULL',
            'contact_number' => '093866124',
            'image' => 'uploads/company/logo.png',
            'email' => 'kongbrosdeth@somnab.com',
            'address' => 'NULL',
            'website' => 'NULL',
        ]);
    }
}
