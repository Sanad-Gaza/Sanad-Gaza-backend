<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\Task;
use App\Models\Grade;

class LanguagesContentSeeder extends Seeder
{
    public function run(): void
    {
        $grade = Grade::firstOrCreate(['name' => 'الصف الأول']);

        // ==========================================
        // أولاً: مادة اللغة العربية
        // ==========================================
        $arabicSubject = Subject::firstOrCreate([
            'grade_id' => $grade->id,
            'name' => 'اللغة العربية'
        ]);

        // التنظيف الذكي للغة العربية
        $oldArabicUnitIds = $arabicSubject->units()->pluck('id');
        if ($oldArabicUnitIds->isNotEmpty()) {
            Task::whereIn('unit_id', $oldArabicUnitIds)->delete();
            $arabicSubject->units()->delete();
        }

        // الوحدة الأولى: حروفي الجميلة
        $arabicUnit1 = Unit::create([
            'subject_id' => $arabicSubject->id,
            'title'      => 'الوحدة الأولى: حروفي الجميلة',
        ]);

        Task::create([
            'unit_id' => $arabicUnit1->id, 'title' => 'أنشودة الحروف',
            'type' => 'video', 'points' => 15, 'url' => 'https://example.com/arabic1'
        ]);
        Task::create([
            'unit_id' => $arabicUnit1->id, 'title' => 'اكتب حرف الألف',
            'type' => 'document', 'points' => 20, 'url' => 'https://example.com/arabic_doc1'
        ]);
        Task::create([
            'unit_id' => $arabicUnit1->id, 'title' => 'اختبار الحروف الأول',
            'type' => 'quiz', 'points' => 40, 'url' => 'https://example.com/arabic_quiz1'
        ]);

        // الوحدة الثانية: عائلتي
        $arabicUnit2 = Unit::create([
            'subject_id' => $arabicSubject->id,
            'title'      => 'الوحدة الثانية: عائلتي',
        ]);

        Task::create([
            'unit_id' => $arabicUnit2->id, 'title' => 'قصة أسرتي',
            'type' => 'video', 'points' => 20, 'url' => 'https://example.com/arabic2'
        ]);
        Task::create([
            'unit_id' => $arabicUnit2->id, 'title' => 'لعبة الكلمات',
            'type' => 'quiz', 'points' => 50, 'url' => 'https://example.com/arabic_quiz2'
        ]);

        // ==========================================
        // ثانياً: مادة اللغة الإنجليزية
        // ==========================================
        $englishSubject = Subject::firstOrCreate([
            'grade_id' => $grade->id,
            'name' => 'اللغة الإنجليزية'
        ]);

        // التنظيف الذكي للغة الإنجليزية
        $oldEnglishUnitIds = $englishSubject->units()->pluck('id');
        if ($oldEnglishUnitIds->isNotEmpty()) {
            Task::whereIn('unit_id', $oldEnglishUnitIds)->delete();
            $englishSubject->units()->delete();
        }

        // الوحدة الأولى: The Alphabet
        $englishUnit1 = Unit::create([
            'subject_id' => $englishSubject->id,
            'title'      => 'Unit 1: The Alphabet',
        ]);

        Task::create([
            'unit_id' => $englishUnit1->id, 'title' => 'ABC Song',
            'type' => 'video', 'points' => 15, 'url' => 'https://example.com/english1'
        ]);
        Task::create([
            'unit_id' => $englishUnit1->id, 'title' => 'Match the letters',
            'type' => 'document', 'points' => 20, 'url' => 'https://example.com/english_doc1'
        ]);
        Task::create([
            'unit_id' => $englishUnit1->id, 'title' => 'Letters Quiz',
            'type' => 'quiz', 'points' => 40, 'url' => 'https://example.com/english_quiz1'
        ]);

        // الوحدة الثانية: Numbers and Colors
        $englishUnit2 = Unit::create([
            'subject_id' => $englishSubject->id,
            'title'      => 'Unit 2: Numbers & Colors',
        ]);

        Task::create([
            'unit_id' => $englishUnit2->id, 'title' => 'Colors of the rainbow',
            'type' => 'video', 'points' => 25, 'url' => 'https://example.com/english2'
        ]);
        Task::create([
            'unit_id' => $englishUnit2->id, 'title' => 'Final Quiz',
            'type' => 'quiz', 'points' => 60, 'url' => 'https://example.com/english_quiz2'
        ]);

        $this->command->info('تم زراعة محتوى اللغتين العربية والإنجليزية بنجاح! 📚');
    }
}
