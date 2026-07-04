<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GradeSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //All Grades From 1 to 9
        Grade::Create(['name' => 'الصف الأول', 'level' => 1, 'description' => 'الصف الأول الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف الثاني', 'level' => 2, 'description' => 'الصف الثاني الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف الثالث', 'level' => 3, 'description' => 'الصف الثالث الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف الرابع', 'level' => 4, 'description' => 'الصف الرابع الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف الخامس', 'level' => 5, 'description' => 'الصف الخامس الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف السادس', 'level' => 6, 'description' => 'الصف السادس الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف السابع', 'level' => 7, 'description' => 'الصف السابع الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف الثامن', 'level' => 8, 'description' => 'الصف الثامن الابتدائي', 'status' => 'active']);
        Grade::Create(['name' => 'الصف التاسع', 'level' => 9, 'description' => 'الصف التاسع الابتدائي', 'status' => 'active']);
    }
}
