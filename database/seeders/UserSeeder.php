<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'Admin',
            'level' => '2',
            'password' =>bcrypt('password'),
        ]);
            // Jika level adalah 2, tambahkan juga ke tabel admins
            if ($user->level == 2) {
                Admin::create([
                    'user_id' => $user->id,
                ]);
    }
}
}
