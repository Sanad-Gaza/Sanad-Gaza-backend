<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;

class AdminDashboardService
{
    public function getDashboardData(): array
    {
        $statistics = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_grades'   => Grade::count(),
            'total_points'   => Student::sum('points_balance'),
        ];

        // جلب أفضل 5 صفوف من حيث النقاط
        $grades = Grade::withSum('students', 'points_balance')
            ->orderByDesc('students_sum_points_balance')
            ->limit(5)
            ->get();

        // تهيئة البيانات
        $topGrades = $grades->map(function ($grade) {

            // أفضل طالب في هذا الصف (الأعلى نقاطاً)
            $topStudent = $grade->students()
                ->with('user') // لجلب اسم الطالب
                ->orderByDesc('points_balance')
                ->first();

            $bestStudentName = $topStudent ? $topStudent->user->full_name : 'لا يوجد طلاب';

            return [
                'grade_name'   => $grade->name,
                'level'        => $grade->level,
                'total_points' => $grade->students_sum_points_balance ?? 0,
                'best_student' => $bestStudentName,
            ];
        });

        return [
            'statistics' => $statistics,
            'top_grades' => $topGrades,
        ];
    }
}
