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
        // 1. جلب مادة الرياضيات الموجودة مسبقاً عبر الـ ID الخاص بها
        $subject = Subject::find(7);

        if (!$subject) {
            $this->command->error('المادة رقم 7 غير موجودة في قاعدة البيانات!');
            return;
        }

        // 2. إنشاء الوحدة الأولى وربطها بالمادة
        $unit1 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الأولى',
        ]);

        // 3. إضافة مهام الوحدة الأولى
        Task::create([
            'unit_id'     => $unit1->id,
            'title'       => 'درس الترتيب التصاعدي',
            'description' => 'فيديو: 4:25 دقيقة',
            'type'        => 'video',
            'url'         => 'https://www.youtube.com/watch?v=example',
            'points'      => 20,
        ]);

        Task::create([
            'unit_id'     => $unit1->id,
            'title'       => 'تدريبات على درس الترتيب التصاعدي',
            'description' => 'ورقة عمل: 2 صفحات',
            'type'        => 'document',
            'url'         => 'https://example.com/worksheet.pdf',
            'points'      => 15,
        ]);

        // 4. إنشاء الوحدة الثانية
        $unit2 = Unit::create([
            'subject_id' => $subject->id,
            'title'      => 'الوحدة الثانية',
        ]);

        // 5. إضافة مهام الوحدة الثانية
        Task::create([
            'unit_id'     => $unit2->id,
            'title'       => 'اختبار الوحدة الثانية',
            'description' => 'اختبار قصير لتقييم المستوى',
            'type'        => 'quiz',
            'url'         => 'https://example.com/quiz',
            'points'      => 50,
        ]);

        $this->command->info('تمت إضافة الوحدات والمهام لمادة الرياضيات بنجاح!');
    }
}
