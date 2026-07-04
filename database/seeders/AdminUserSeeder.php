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
                // الحقول الجديدة بدلاً من 'name'
                'identity_number'  => '100000000', // رقم هوية افتراضي للآدمن
                'first_name'       => 'مدير',
                'father_name'      => 'النظام',
                'grandfather_name' => '',
                'family_name'      => '',

                'email'            => 'admin@sanad.com',
                'phone_number'     => null,
                'role'             => User::ROLE_ADMIN,
                'status'           => User::STATUS_ACTIVE,
                'profile_picture'  => null,
                'password'         => Hash::make('Admin@12345'),
            ]
        );
    }
}
