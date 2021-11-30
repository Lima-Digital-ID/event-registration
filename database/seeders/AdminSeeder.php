<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newUser = new \App\Models\User;
        $newUser->name = 'Administrator';
        $newUser->email = 'administrator@mail.com';
        $newUser->password = \Hash::make('12345678');
        $newUser->level = 'Administrator';

        $newUser->save();

        $newUser = new \App\Models\User;
        $newUser->name = 'Panitia 1';
        $newUser->email = 'panitia1@mail.com';
        $newUser->password = \Hash::make('12345678');
        $newUser->level = 'Panitia';

        $newUser->save();
    }
}
