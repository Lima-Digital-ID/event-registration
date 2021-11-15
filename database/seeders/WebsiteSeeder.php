<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newWebsite = new \App\Models\Website;
        $newWebsite->judul = 'Absensi Event';
        $newWebsite->email = 'event@mail.com';
        $newWebsite->alamat = 'Bondowoso';

        $newWebsite->save();
    }
}
