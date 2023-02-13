<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // php artisan cache:forget spatie.permission.cache
    // php artisan db:seed --class=DatabaseSeeder
    public function run()
    {
        $this->call([
            CompanySeeder::class,
            CreateAdminUserSeeder::class,
            PermissionTableSeeder::class,
            SettingSeeder::class,
            ClientSeeder::class,
            PaymentMethodSeeder::class,
            AttributeSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
