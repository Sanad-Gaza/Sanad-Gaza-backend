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

        $this->call([GradeSeeder::class]);

        $this->call([SubjectSeeder::class]);

        $this->call([TeacherSeeder::class]);

        $this->call([StudentSeeder::class]);

        $this->call([SubjectContentSeeder::class]);

        $this->call([LanguagesContentSeeder::class]);
    }
}
