<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Lisa Davis',
                'email' => 'lisa@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'robert@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Emily Taylor',
                'email' => 'emily@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'James Anderson',
                'email' => 'james@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Emma Thomas',
                'email' => 'emma@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Michael Jackson',
                'email' => 'michael@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Sophia White',
                'email' => 'sophia@example.com',
                'password' => 'password123'
            ]
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password'])
            ]);
        }
    }
} 