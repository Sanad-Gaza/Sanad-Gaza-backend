<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SubjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Subject::Create(['grade_id' => 1, 'name' => 'اللغة العربية', 'description' => 'اللغة العربية للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'اللغة الإنجليزية', 'description' => 'اللغة الإنجليزية للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'علوم', 'description' => 'علوم للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'تربية إسلامية', 'description' => 'تربية إسلامية للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'تربية فنية', 'description' => 'تربية فنية للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'تربية بدنية', 'description' => 'تربية بدنية للصف الأول', 'status' => 'active']);
        Subject::Create(['grade_id' => 1, 'name' => 'رياضيات', 'description' => 'رياضيات للصف الأول', 'status' => 'active']);
    }
}
