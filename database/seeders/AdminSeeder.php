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
        $newUser->password = \Hash::make('mwb546hs51');
        $newUser->level = 'Administrator';

        $newUser->save();
    }
}
