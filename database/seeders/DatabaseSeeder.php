<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([AdminUserSeeder::class]);

        // $this->call([GradeSeeder::class]);

        // $this->call([SubjectSeeder2::class]);

        // $this->call([TeacherSeeder2::class]);

        // $this->call([StudentSeeder2::class]);

        // $this->call([SubjectContentSeeder2::class]);

        // $this->call([LanguagesContentSeeder2::class]);
    }
}
