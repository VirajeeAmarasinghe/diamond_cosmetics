<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_user = new User();
        $admin_user->name = 'Virajee Amarasinghe';
        $admin_user->email = 'virajee.hiranthika@gmail.com';
        $admin_user->password = Hash::make('123');
        $admin_user->save();

        $admin_user->assignRole('admin');

        $moderator_user = new User();
        $moderator_user->name = 'Ruvindu Amarasinghe';
        $moderator_user->email = 'ruvindu.amarasinghe@gmail.com';
        $moderator_user->password = Hash::make('123');
        $moderator_user->save();

        $moderator_user->assignRole('moderator');
    }
}
