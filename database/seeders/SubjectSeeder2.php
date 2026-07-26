<?php
namespace Database\Seeders;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class SubjectSeeder2 extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Subject::Create(['grade_id' => 2, 'name' => 'اللغة العربية', 'description' => 'اللغة العربية للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'اللغة الإنجليزية', 'description' => 'اللغة الإنجليزية للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'علوم', 'description' => 'علوم للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'تربية إسلامية', 'description' => 'تربية إسلامية للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'تربية فنية', 'description' => 'تربية فنية للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'تربية بدنية', 'description' => 'تربية بدنية للصف الثاني', 'status' => 'active']);
        Subject::Create(['grade_id' => 2, 'name' => 'رياضيات', 'description' => 'رياضيات للصف الثاني', 'status' => 'active']);
    }
}
