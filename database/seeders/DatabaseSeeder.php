<?php

namespace Database\Seeders;

use App\Models\Grade;
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

        $this->call([AdminUserSeeder::class]);

        $this->call([GradeSeeder::class]);

        $this->call([SubjectSeeder::class]);

        $this->call([StudentSeeder::class]);

    }
}
