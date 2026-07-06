<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubjectContentController extends Controller
{
    public function getContent($subject_id)
    {
        // 1. جلب بيانات الطالب الحالي
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json(['message' => 'بيانات الطالب غير متوفرة'], 404);
        }

        // 2. التحقق من وجود المادة وجلب وحداتها ومهامها
        // استخدمنا with لجلب كل شيء باستعلام واحد فقط
        $subject = Subject::with('units.tasks')->findOrFail($subject_id);

        // 3. جلب أرقام (IDs) المهام التي أنجزها الطالب (لحل مشكلة N+1 وتقليل الاستعلامات)
        $completedTaskIds = $student->tasks()
            ->wherePivot('status', 'completed')
            ->pluck('tasks.id') // نحتاج الـ ID فقط
            ->toArray();

        // 4. تنسيق البيانات (Formatting) لترجع بالشكل الذي يتوقعه مبرمج الـ Frontend
        $formattedUnits = $subject->units->map(function ($unit) use ($completedTaskIds) {

            // حساب إجمالي النقاط التي جمعها الطالب من هذه الوحدة (بناءً على طلبك في شروط القبول)
            $unitAchievedPoints = 0;

            $formattedTasks = $unit->tasks->map(function ($task) use ($completedTaskIds, &$unitAchievedPoints) {
                // فحص بسيط في الذاكرة: هل الـ ID الخاص بالمهمة موجود ضمن المهام المنجزة؟
                $isCompleted = in_array($task->id, $completedTaskIds);

                if ($isCompleted) {
                    $unitAchievedPoints += $task->points; // إذا منجزة، نجمع نقاطها
                }

                return [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'type'         => $task->type, // 'video', 'document', 'quiz'
                    'url'          => $task->url,
                    'points'       => $task->points,
                    'is_completed' => $isCompleted, // هذا المفتاح سيسهل عمل الواجهات جداً (True/False)
                ];
            });

            return [
                'unit_id'               => $unit->id,
                'unit_title'            => $unit->title,
                'achieved_points'       => $unitAchievedPoints, // النقاط المنجزة في هذه الوحدة
                'total_unit_points'     => $unit->tasks->sum('points'), // إجمالي النقاط المتاحة في الوحدة
                'tasks'                 => $formattedTasks,
            ];
        });

        // 5. إرسال الرد النهائي
        return response()->json([
            'subject_id'   => $subject->id,
            'subject_name' => $subject->name, // تأكد أن حقل اسم المادة في جدولك هو name أو غيره حسب تصميمك
            'content'      => $formattedUnits,
        ]);
    }




    public function completeTask(Request $request, $task_id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json(['message' => 'بيانات الطالب غير متوفرة'], 404);
        }

        $task = Task::find($task_id);

        if (!$task) {
            return response()->json(['message' => 'المهمة غير موجودة'], 404);
        }

        $existingRecord = $student->tasks()->wherePivot('task_id', $task_id)->first();

        if ($existingRecord && $existingRecord->pivot->status === 'completed') {
            return response()->json([
                'message' => 'لقد قمت بإنجاز هذه المهمة مسبقاً!',
                'already_completed' => true
            ], 200);
        }

        // تسجيل المهمة كمكتملة
        if ($existingRecord) {
            $student->tasks()->updateExistingPivot($task_id, [
                'status'       => 'completed',
                'completed_at' => \Carbon\Carbon::now(),
            ]);
        } else {
            $student->tasks()->attach($task_id, [
                'status'       => 'completed',
                'completed_at' => \Carbon\Carbon::now(),
            ]);
        }

        // إضافة النقاط للمحفظة الكلية
        $student->points_balance += $task->points;
        $student->save();

        // ========================================================
        // نظام الترقية التلقائي المحمي (BULLETPROOF LEVEL-UP LOGIC)
        // ========================================================
        $currentUnit = $task->unit;

        $totalUnitTasks = $currentUnit->tasks()->count();

        $completedUnitTasks = $student->tasks()
            ->whereHas('unit', function ($query) use ($currentUnit) {
                $query->where('id', $currentUnit->id);
            })
            ->wherePivot('status', 'completed')
            ->count();

        $levelUpgraded = false;

        if ($completedUnitTasks === $totalUnitTasks) {
            $levelUpgraded = true;

            // الفحص الذكي: هل يوجد سجل للطالب في هذه الوحدة؟
            $unitExists = $student->units()->where('unit_id', $currentUnit->id)->exists();

            if ($unitExists) {
                // إذا كان موجوداً، نحدثه إلى مكتمل ونمنحه 3 نجوم
                $student->units()->updateExistingPivot($currentUnit->id, [
                    'status' => 'completed',
                    'stars'  => 3
                ]);
            } else {
                // إذا لم يكن موجوداً (قاعدة بيانات جديدة)، ننشئه فوراً كمكتمل مع 3 نجوم
                $student->units()->attach($currentUnit->id, [
                    'status' => 'completed',
                    'stars'  => 3
                ]);
            }

            // فتح المستوى التالي تلقائياً
            $nextUnit = Unit::where('subject_id', $currentUnit->subject_id)
                ->where('id', '>', $currentUnit->id)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextUnit) {
                // نتحقق أيضاً من عدم تكرار الوحدة التالية
                $nextUnitExists = $student->units()->where('unit_id', $nextUnit->id)->exists();
                if (!$nextUnitExists) {
                    $student->units()->attach($nextUnit->id, [
                        'status' => 'unlocked',
                        'stars'  => 0
                    ]);
                }
            }
        }
        // ========================================================

        return response()->json([
            'message'        => 'بطل يا بطل! مستوى متميز',
            'earned_points'  => $task->points,
            'new_balance'    => $student->points_balance,
            'level_upgraded' => $levelUpgraded // متغير مفيد جداً لعهد لتشغيل أنيميشن الترقية في الواجهة
        ], 200);
    }





    public function getSubjectMap($subject_id)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json(['message' => 'بيانات الطالب غير متوفرة'], 404);
        }

        // جلب المادة مع وحداتها (مستوياتها) ومهامها
        $subject = Subject::with(['units.tasks'])->find($subject_id);

        if (!$subject) {
            return response()->json(['message' => 'المادة غير موجودة'], 404);
        }

        // متغيرات لحساب التقدم العام للمادة
        $totalSubjectTasks = 0;
        $completedSubjectTasks = 0;
        $totalSubjectPoints = 0;
        $achievedSubjectPoints = 0;

        $mapData = [];

        // المرور على كل وحدة (مستوى) في المادة
        foreach ($subject->units as $index => $unit) {
            // البحث عن حالة الطالب في هذا المستوى بالتحديد
            $unitProgress = $student->units()->where('unit_id', $unit->id)->first();

            $status = 'locked'; // الحالة الافتراضية
            $stars = 0;

            if ($unitProgress) {
                // إذا كان له سجل سابق، نأخذ حالته ونجومه
                $status = $unitProgress->pivot->status;
                $stars = $unitProgress->pivot->stars;
            } elseif ($index === 0) {
                // الحركة الذكية: إذا لم يكن له سجل، وهذه هي "الوحدة الأولى"، نفتحها له تلقائياً
                $status = 'unlocked';
                $student->units()->attach($unit->id, ['status' => 'unlocked', 'stars' => 0]);
            }

            // معالجة مهام هذا المستوى
            $unitTasks = [];
            foreach ($unit->tasks as $task) {
                $totalSubjectTasks++;
                $totalSubjectPoints += $task->points;

                // التحقق مما إذا كان الطالب أنجز هذه المهمة
                $isCompleted = $student->tasks()->where('task_id', $task->id)
                    ->wherePivot('status', 'completed')->exists();

                if ($isCompleted) {
                    $completedSubjectTasks++;
                    $achievedSubjectPoints += $task->points;
                }

                $unitTasks[] = [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'description'  => $task->description, // مهم لعرض تفاصيل المهمة
                    'type'         => $task->type,
                    'url'          => $task->url,         // مهم جداً لفتح الفيديو أو الملف
                    'points'       => $task->points,
                    'is_completed' => $isCompleted
                ];
            }

            // تجميع بيانات المستوى الحالي
            $mapData[] = [
                'level_id'    => $unit->id,
                'level_title' => $unit->title,
                'status'      => $status,
                'stars'       => $stars,
                'tasks'       => $unitTasks
            ];
        }

        // حساب النسبة المئوية لتقدم المادة (بدون كسور عشرية)
        $progressPercentage = $totalSubjectTasks > 0 ? round(($completedSubjectTasks / $totalSubjectTasks) * 100) : 0;

        // إرجاع الاستجابة النهائية المهيكلة
        return response()->json([
            'subject_id'                  => $subject->id,
            'subject_name'                => $subject->name,
            'overall_progress_percentage' => $progressPercentage,
            'achieved_points'             => $achievedSubjectPoints,
            'total_points'                => $totalSubjectPoints,
            'levels_map'                  => $mapData
        ], 200);
    }
}
