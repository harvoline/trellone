<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Trellone Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        $user->api_token = $token;
        $user->save();
    }
}
