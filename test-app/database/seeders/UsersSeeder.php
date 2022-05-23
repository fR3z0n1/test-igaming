<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'igaming', 'email' => 'igaming@gmail.com', 'password' => Hash::make('test123'), 'api_token' => Str::random(60)],
            ['name' => 'test2', 'email' => 'test2@gmail.com', 'password' => Hash::make('test123'), 'api_token' => Str::random(60)],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
