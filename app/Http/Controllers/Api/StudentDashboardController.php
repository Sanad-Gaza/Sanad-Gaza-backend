<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $student = $user->student;

    if (!$student) {
        return response()->json(['message' => 'بيانات الطالب غير متوفرة'], 404);
    }

    // 2. حساب ترتيب الطالب (نشطين فقط)
    $StudentsTopThanMe = Student::where('grade_id', $student->grade_id)
        ->whereHas('user', function ($query) {
            $query->where('status', 'active');
        })
        ->where('points_balance', '>', $student->points_balance)
        ->count();

    $rank = $StudentsTopThanMe + 1;

    // 3. جلب أفضل 3 طلاب في نفس الصف (نشطين)
    $leaderboard = Student::with('user')
        ->where('grade_id', $student->grade_id)
        ->whereHas('user', function ($query) {
            $query->where('status', 'active');
        })
        ->orderBy('points_balance', 'desc')
        ->take(3)
        ->get();

    // 4. تحديث الشعلة (Daily Streak)
    $lastActivity = $student->last_activity_date ? \Carbon\Carbon::parse($student->last_activity_date) : null;

    if (is_null($lastActivity) || (!$lastActivity->isToday() && !$lastActivity->isYesterday())) {
        $student->daily_streak = 1;
    } elseif ($lastActivity->isYesterday()) {
        $student->daily_streak += 1;
    }

    $student->last_activity_date = \Carbon\Carbon::now();
    $student->save();

    // 5. إحصائيات المهام
    $completedTasksCount = $student->tasks()->wherePivot('status', 'completed')->count();
    $pendingTasksCount = $student->tasks()->wherePivot('status', 'pending')->count();

    // 6. جلب دروس اليوم (حسب صف الطالب وتاريخ اليوم)
    $todayLessons = Lesson::where('grade_id', $student->grade_id)
        ->whereDate('scheduled_date', Carbon::today())
        ->get();

    return response()->json([
        'student_name'          => $user->full_name,
        'points_balance'        => $student->points_balance,
        'daily_streak'          => $student->daily_streak,
        'rank'                  => $rank,
        'leaderboard'           => $leaderboard,
        'completed_tasks_count' => $completedTasksCount,
        'pending_tasks_count'   => $pendingTasksCount,
        'today_lessons'         => $todayLessons,
    ]);
}
}
