<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class TeacherDashboardService
{
    /**
     * الدالة الرئيسية التي يطلبها الكنترولر
     */
    public function getDashboardData($teacher)
    {
        return [
            'top_stats' => $this->getTopStats($teacher),
            // سنقوم بإضافة باقي الأقسام هنا تباعاً
        ];
    }


    /**
     * 1. الإحصائيات العلوية
     */
   private function getTopStats($teacher)
    {
        // استخدام التخزين المؤقت (Cache) لمدة 15 دقيقة لتخفيف الضغط على قاعدة البيانات
        return Cache::remember("teacher_top_stats_{$teacher->id}", now()->addMinutes(15), function () use ($teacher) {

            // 1. نجلب أرقام (IDs) الشعب التي يدرسها المعلم
            $sectionIds = $teacher->sections()->pluck('sections.id');

            // 2. نجلب أرقام (IDs) الصفوف التابعة لهذه الشعب (لمعرفة عدد الصفوف ولربطها بالدروس)
            $gradeIds = $teacher->sections()->with('grade')->get()->pluck('grade.id')->unique();

            // ⚠️ التصحيح هنا: حساب إجمالي الطلاب المسجلين في "شعب" المعلم فقط
            $totalStudents = Student::whereIn('section_id', $sectionIds)->count();

            // ⚠️ التصحيح هنا: حساب إجمالي نقاط الطلاب المسجلين في "شعب" المعلم فقط
            $totalPoints = Student::whereIn('section_id', $sectionIds)->sum('points_balance');

            // 4. إجمالي عدد الصفوف التي يدرسها
            $totalGrades = $gradeIds->count();

            // 5. إجمالي الدروس (الدروس التي تطابق مادة المعلم وتندرج تحت الصفوف التي يدرسها)
            $totalLessons = Lesson::where('subject_id', $teacher->subject_id)
                ->whereIn('grade_id', $gradeIds)
                ->count();

            // إرجاع البيانات بشكل متوافق مع الواجهة
            return [
                'total_students' => $totalStudents,
                'total_points'   => (int) $totalPoints, // تحويل إلى int لضمان التنسيق
                'total_grades'   => $totalGrades,
                'total_lessons'  => $totalLessons,
            ];
        });
    }
}
