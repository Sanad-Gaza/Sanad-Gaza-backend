<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $grade = Grade::firstOrCreate([
            'name'        => 'الصف الأول',
            'level'       => 1,
            'description' => 'الصف الأول الابتدائي',
            'status'      => 'active'
        ]);

        // التأكد من جلب المواد الصحيحة للربط
        $math = Subject::firstOrCreate(['name' => 'رياضيات', 'grade_id' => $grade->id]);
        $arabic = Subject::firstOrCreate(['name' => 'اللغة العربية', 'grade_id' => $grade->id]);
        $english = Subject::firstOrCreate(['name' => 'اللغة الإنجليزية', 'grade_id' => $grade->id]);

        $teachers = [
            [
                'id_num'          => '900000001',
                'first'           => 'محمود',
                'father'          => 'أحمد',
                'grand'           => 'محمد',
                'family'          => 'الخوارزمي',
                'user'            => 't_math',
                'email'           => 'teacher.math@sanad.com',
                'gender'          => 'male',
                'subject_id'      => $math->id,
                'role'            => 'teacher',
                'specialization'  => 'Math',
                'graduation_year' => '2020',
                'qualification'   => 'Bachelor of Science in Mathematics',
                'bio'             => 'معلم رياضيات ذو خبرة واسعة في تدريس الطلاب بمختلف المستويات التعليمية.',
                'birth_date'      => '1985-04-12'
            ],
            [
                'id_num'          => '900000002',
                'first'           => 'فاطمة',
                'father'          => 'أحمد',
                'grand'           => 'محمد',
                'family'          => 'الفراهيدي',
                'user'            => 't_arabic',
                'email'           => 'teacher.arabic@sanad.com',
                'gender'          => 'female',
                'subject_id'      => $arabic->id,
                'role'            => 'teacher',
                'specialization'  => 'Arabic',
                'graduation_year' => '2018',
                'qualification'   => 'Bachelor of Arts in Arabic Literature',
                'bio'             => 'معلمة لغة عربية ذات خبرة واسعة في تدريس الطلاب بمختلف المستويات التعليمية.',
                'birth_date'      => '1990-08-22'
            ],
            [
                'id_num'          => '900000003',
                'first'           => 'جون',
                'father'          => 'أحمد',
                'grand'           => 'محمد',
                'family'          => 'سميث',
                'user'            => 't_english',
                'email'           => 'teacher.english@sanad.com',
                'gender'          => 'male',
                'subject_id'      => $english->id,
                'role'            => 'teacher',
                'specialization'  => 'English',
                'graduation_year' => '2019',
                'qualification'   => 'Bachelor of Arts in English',
                'bio'             => 'معلم اللغة الإنجليزية ذو خبرة واسعة في تدريس الطلاب بمختلف المستويات التعليمية.',
                'birth_date'      => '1988-11-05'
            ],
        ];

        foreach ($teachers as $t) {
            $user = User::firstOrCreate(
                ['email' => $t['email']],
                [
                    'identity_number'  => $t['id_num'],
                    'first_name'       => $t['first'],
                    'father_name'      => $t['father'],
                    'grandfather_name' => $t['grand'],
                    'family_name'      => $t['family'],
                    'username'         => $t['user'],
                    'role'             => 'teacher',
                    'phone_number'     => $t['id_num'], // رقم الهاتف مؤقتًا نفس رقم الهوية
                    'password'         => bcrypt('password'),
                ]
            );

            // هنا يكمن التحديث: تم ربط الحقول الجديدة بقاعدة البيانات!
            Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'subject_id'      => $t['subject_id'],
                    'gender'          => $t['gender'],
                    'specialization'  => $t['specialization'],
                    'bio'             => $t['bio'],
                    'qualification'   => $t['qualification'],
                    'graduation_year' => $t['graduation_year'],
                    'birth_date'      => $t['birth_date'],
                ]
            );
        }

        $this->command->info('تم زراعة المعلمين الثلاثة ببياناتهم الثابتة الشاملة بنجاح! 👨‍🏫');
    }
}
