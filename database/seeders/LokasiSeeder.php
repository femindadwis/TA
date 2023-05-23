<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lokasi')->insert([
            'name' => 'TPA PECUK',
            'alamat' => 'PECUK',
            'lng' => '108.3019469',
            'lat' => '-6.3404209',
            'foto' => 'Lokasi/1.jpg',
        ]);
    }
}
