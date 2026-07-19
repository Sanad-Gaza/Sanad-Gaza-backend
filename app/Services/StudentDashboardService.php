<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class StudentDashboardService
{
    public function getDashboardData(User $user): array
    {
        $student = $user->student;

        if (!$student) {
            throw ValidationException::withMessages(['student' => 'بيانات الطالب غير متوفرة']);
        }

        // 1. حساب الترتيب (Rank)
        $studentsTopThanMe = Student::where('grade_id', $student->grade_id)
            ->whereHas('user', fn ($query) => $query->where('status', 'active'))
            ->where('points_balance', '>', $student->points_balance)
            ->count();
        $rank = $studentsTopThanMe + 1;

        // 2. جلب لوحة المتصدرين (Leaderboard)
        $leaderboard = Student::with('user')
            ->where('grade_id', $student->grade_id)
            ->whereHas('user', fn ($query) => $query->where('status', 'active'))
            ->orderByDesc('points_balance')
            ->take(3)
            ->get();

        // 3. تحديث الشعلة (Daily Streak) وتاريخ النشاط
        $lastActivity = $student->last_activity_date ? Carbon::parse($student->last_activity_date) : null;

        if (is_null($lastActivity) || (!$lastActivity->isToday() && !$lastActivity->isYesterday())) {
            $student->daily_streak = 1;
        } elseif ($lastActivity->isYesterday()) {
            $student->daily_streak += 1;
        }

        $student->last_activity_date = Carbon::now();
        $student->save();

        // 4. إحصائيات المهام والدروس
        $completedTasksCount = $student->tasks()->wherePivot('status', 'completed')->count();
        $pendingTasksCount = $student->tasks()->wherePivot('status', 'pending')->count();

        $todayLessons = Lesson::where('grade_id', $student->grade_id)
            ->whereDate('scheduled_date', Carbon::today())
            ->get();

        return [
            'student_name'          => $user->full_name,
            'grade_id'              => $student->grade_id,
            'grade_name'            => $student->grade->name,
            'points_balance'        => $student->points_balance,
            'daily_streak'          => $student->daily_streak,
            'rank'                  => $rank,
            'leaderboard'           => $leaderboard,
            'completed_tasks_count' => $completedTasksCount,
            'pending_tasks_count'   => $pendingTasksCount,
            'today_lessons'         => $todayLessons,
        ];
    }
}
