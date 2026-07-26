<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class StudentSeeder2 extends Seeder
{
    public function run(): void
    {
        $grade = Grade::firstOrCreate([
            'name'        => 'الصف الثاني',
            'level'       => 2,
            'description' => 'الصف الثاني الابتدائي',
            'status'      => 'active'
        ]);

        $students = [
            ['id_num' => '200000001', 'first' => 'ضياء', 'father' => 'أكثم', 'grand' => 'أنس', 'family' => 'الشهري', 'gender' => 'female', 'points' => 802],
            ['id_num' => '200000002', 'first' => 'عبدالرؤوف', 'father' => 'وائل', 'grand' => 'احمد', 'family' => 'السهلي', 'gender' => 'male', 'points' => 695],
            ['id_num' => '200000003', 'first' => 'تالة', 'father' => 'المنصور', 'grand' => 'يزن', 'family' => 'العقل', 'gender' => 'male', 'points' => 246],
            ['id_num' => '200000004', 'first' => 'إسراء', 'father' => 'وديع', 'grand' => 'وعد', 'family' => 'السماري', 'gender' => 'male', 'points' => 452],
            ['id_num' => '200000005', 'first' => 'جعفر', 'father' => 'جمال', 'grand' => 'حامد', 'family' => 'الحصين', 'gender' => 'male', 'points' => 50],
            ['id_num' => '200000006', 'first' => 'وصفي', 'father' => 'جبير', 'grand' => 'طلال', 'family' => 'الحسين', 'gender' => 'male', 'points' => 73],
            ['id_num' => '200000007', 'first' => 'الليث', 'father' => 'عبد الحليم', 'grand' => 'وجيه', 'family' => 'الفيفي', 'gender' => 'female', 'points' => 952],
            ['id_num' => '200000008', 'first' => 'ايوب', 'father' => 'عبد الهادي', 'grand' => 'جريس', 'family' => 'الداوود', 'gender' => 'male', 'points' => 640],
            ['id_num' => '200000009', 'first' => 'ميلاء', 'father' => 'مسعود', 'grand' => 'مناف', 'family' => 'الراجحي', 'gender' => 'female', 'points' => 527],
            ['id_num' => '200000010', 'first' => 'مديحة', 'father' => 'وليد', 'grand' => 'مخلص', 'family' => 'الشيباني', 'gender' => 'female', 'points' => 645],
            ['id_num' => '200000011', 'first' => 'مضر', 'father' => 'وسام', 'grand' => 'ابراهيم', 'family' => 'الشيباني', 'gender' => 'male', 'points' => 142],
            ['id_num' => '200000012', 'first' => 'آمنة', 'father' => 'شهاب', 'grand' => 'مسلم', 'family' => 'السويلم', 'gender' => 'female', 'points' => 654],
            ['id_num' => '200000013', 'first' => 'عملا', 'father' => 'عوده', 'grand' => 'عادل', 'family' => 'الراجحي', 'gender' => 'female', 'points' => 595],
            ['id_num' => '200000014', 'first' => 'سامح', 'father' => 'منتصر', 'grand' => 'شاكر', 'family' => 'الماجد', 'gender' => 'male', 'points' => 203],
            ['id_num' => '200000015', 'first' => 'فواز', 'father' => 'حمدي', 'grand' => 'نوفان', 'family' => 'القحطاني', 'gender' => 'male', 'points' => 781],
            ['id_num' => '200000016', 'first' => 'تغريد', 'father' => 'وحيد', 'grand' => 'عبدالقادر', 'family' => 'برماوي', 'gender' => 'male', 'points' => 799],
            ['id_num' => '200000017', 'first' => 'بدوان', 'father' => 'اسعد', 'grand' => 'الياس', 'family' => 'السويلم', 'gender' => 'female', 'points' => 558],
            ['id_num' => '200000018', 'first' => 'سعود', 'father' => 'راضي', 'grand' => 'تركي', 'family' => 'الماجد', 'gender' => 'female', 'points' => 876],
            ['id_num' => '200000019', 'first' => 'محبوبة', 'father' => 'محمد', 'grand' => 'ضرغام', 'family' => 'المشيقح', 'gender' => 'female', 'points' => 975],
            ['id_num' => '200000020', 'first' => 'أمل', 'father' => 'مهدي', 'grand' => 'صهيب', 'family' => 'الصقير', 'gender' => 'female', 'points' => 701],
        ];

        $i = 1;

        foreach ($students as $s) {
            $user = User::firstOrCreate(
                ['email' => "student22{$i}@sanad.com"],
                [
                    'identity_number'  => $s['id_num'],
                    'first_name'       => $s['first']."{$i}",
                    'father_name'      => $s['father']."{$i}",
                    'grandfather_name' => $s['grand']."{$i}",
                    'family_name'      => $s['family']."{$i}",
                    'username'         => "student22{$i}",
                    'role'             => 'student',
                    'phone_number'     => $s['id_num'].'222', // رقم الهاتف مؤقتًا نفس رقم الهوية
                    'password'         => bcrypt('password'),
                ]
            );

            // تحديد الشعبة برمجياً: أول 10 طلاب في شعبة "أ"، والباقي في "ب"
            $sectionName = ($i <= 10) ? 'أ' : 'ب';

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'grade_id'       => $grade->id,
                    'gender'         => $s['gender'],
                    'points_balance' => $s['points'],
                    'section'        => $sectionName, // تم ربط الشعبة هنا!
                ]
            );

            $i++;
        }

        $this->command->info('تم تثبيت بيانات 20 طالباً وتوزيعهم على الشعبتين (أ) و (ب) بنجاح! 🏆');
    }
}
