<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(['id' => 1], [
            'name' => 'Admin',
            'email' => 'kongbrosdeth@somnab.com',
            'contact_number' => '093866124',
            'is_active' => 1,
            'password' => bcrypt('11112222'),
        ]);
        $role = Role::updateOrCreate(['id' => 1], ['name' => 'Admin']);
        $user->assignRole([$role->id]);
    }
}
