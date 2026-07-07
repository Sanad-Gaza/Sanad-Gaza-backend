<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\Task;

class SubjectContentSeeder extends Seeder
{
    public function run(): void
    {
        $subject = Subject::where('grade_id', 1)->where('name', 'رياضيات')->first();

        if (!$subject) {
            $this->command->error('مادة الرياضيات غير موجودة في قاعدة البيانات!');
            return;
        }

        $oldUnitIds = $subject->units()->pluck('id');

        if ($oldUnitIds->isNotEmpty()) {
            Task::whereIn('unit_id', $oldUnitIds)->delete();

            $subject->units()->delete();

            $this->command->warn('تم تنظيف الوحدات والمهام القديمة لمادة الرياضيات.');
        }

        $unit1 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الأولى: الأعداد حتى 9',
        ]);

        Task::create([
            'unit_id'     => $unit1->id,
            'title'       => 'درس الترتيب التصاعدي',
            'description' => 'فيديو: 4:25 دقيقة',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example1',
            'points'      => 20,
        ]);

        Task::create([
            'unit_id'     => $unit1->id,
            'title'       => 'تدريبات على درس الترتيب التصاعدي',
            'description' => 'ورقة عمل: 2 صفحات',
            'type'        => 'document',
            'url'         => 'https://example.com/worksheet1.pdf',
            'points'      => 15,
        ]);

        Task::create([
            'unit_id'     => $unit1->id,
            'title'       => 'اختبار الوحدة الأولى',
            'description' => 'اختبار قصير لتقييم المستوى',
            'type'        => 'quiz',
            'url'         => 'https://example.com/quiz1',
            'points'      => 30,
        ]);


        $unit2 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الثانية: الجمع الجميل',
        ]);

        Task::create([
            'unit_id'     => $unit2->id,
            'title'       => 'ما هو الجمع؟',
            'description' => 'فيديو قصة التفاحات: 4 دقائق',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example2',
            'points'      => 15,
        ]);

        Task::create([
            'unit_id'     => $unit2->id,
            'title'       => 'اختبار أبطال الجمع',
            'description' => '10 أسئلة سريعة',
            'type'        => 'quiz',
            'url'         => 'https://example.com/quiz2',
            'points'      => 40,
        ]);


        $unit3 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الثالثة: الطرح السريع',
        ]);

        Task::create([
            'unit_id'     => $unit3->id,
            'title'       => 'أين اختفت الحلوى؟',
            'description' => 'فيديو شرح الطرح: 5 دقائق',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example3',
            'points'      => 20,
        ]);

        Task::create([
            'unit_id'     => $unit3->id,
            'title'       => 'لعبة الطرح بالصور',
            'description' => 'ورقة عمل استكشافية',
            'type'        => 'document',
            'url'         => 'https://example.com/worksheet3.pdf',
            'points'      => 15,
        ]);

        Task::create([
            'unit_id'     => $unit3->id,
            'title'       => 'الاختبار الكبير للجمع والطرح',
            'description' => 'تحدي يجمع بين الوحدتين',
            'type'        => 'quiz',
            'url'         => 'https://example.com/quiz3',
            'points'      => 50,
        ]);


        $unit4 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الرابعة: الأشكال الهندسية',
        ]);

        Task::create([
            'unit_id'     => $unit4->id,
            'title'       => 'مربع، مثلث، ودائرة',
            'description' => 'أغنية الأشكال: 3 دقائق',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example4',
            'points'      => 15,
        ]);

        Task::create([
            'unit_id'     => $unit4->id,
            'title'       => 'ارسم منزلك بالأشكال',
            'description' => 'نشاط فني',
            'type'        => 'document',
            'url'         => 'https://example.com/worksheet4.pdf',
            'points'      => 25,
        ]);

        $unit5 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الخامسة: القياس والأطوال',
        ]);

        Task::create([
            'unit_id'     => $unit5->id,
            'title'       => 'من الأطول؟',
            'description' => 'فيديو مقارنة الأطوال: 4 دقائق',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example5',
            'points'      => 20,
        ]);

        Task::create([
            'unit_id'     => $unit5->id,
            'title'       => 'اختبار نهاية الفصل',
            'description' => 'الاختبار الذهبي لإنهاء المادة',
            'type'        => 'quiz',
            'url'         => 'https://example.com/quiz5',
            'points'      => 100,
        ]);

        $this->command->info('تمت إضافة 5 وحدات و13 مهمة لمادة الرياضيات بنجاح!');
    }
}
