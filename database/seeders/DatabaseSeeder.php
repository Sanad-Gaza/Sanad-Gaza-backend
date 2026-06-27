<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([AdminUserSeeder::class]);

        // create a default user for testing type Student
        User::updateOrCreate(
            [
                'username' => 'student1',
            ],
            [
                'name' => 'Student One',
                'email' => 'student1@sanad.com',
                'phone_number' => null,
                'role' => User::ROLE_STUDENT,
                'status' => User::STATUS_ACTIVE,
                'profile_picture' => null,
                'password' => Hash::make('123'),
            ]
        );


        // create a default user for testing type Teacher
        User::updateOrCreate(
            [
                'username' => 'teacher1',
            ],
            [
                'name' => 'Teacher One',
                'email' => 'teacher1@sanad.com',
                'phone_number' => null,
                'role' => User::ROLE_TEACHER,
                'status' => User::STATUS_ACTIVE,
                'profile_picture' => null,
                'password' => Hash::make('123'),
            ]
        );
    }
}


