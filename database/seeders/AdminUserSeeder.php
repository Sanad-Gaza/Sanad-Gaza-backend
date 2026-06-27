<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'name' => 'System Admin',
                'email' => 'admin@sanad.com',
                'phone_number' => null,
                'role' => User::ROLE_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'profile_picture' => null,
                'password' => Hash::make('Admin@12345'),
            ]
        );


        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                [
                    'username' => 'student' . $i,
                ],
                [
                    'name' => 'ابراهيم ',
                    'email' => 'student' . $i . '@sanad.com',
                    'phone_number' => null,
                    'role' => User::ROLE_STUDENT,
                    'status' => User::STATUS_ACTIVE,
                    'profile_picture' => null,
                    'password' => Hash::make('123'),
                ]
            );
        }
    }
}
