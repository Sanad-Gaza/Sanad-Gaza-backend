<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Task;
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
        // 1. جلب بيانات الطالب الحالي
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return response()->json(['message' => 'بيانات الطالب غير متوفرة'], 404);
        }

        // 2. التحقق من وجود المهمة
        $task = Task::find($task_id);

        if (!$task) {
            return response()->json(['message' => 'المهمة غير موجودة'], 404);
        }

        // 3. التحقق مما إذا كان الطالب قد أنجز هذه المهمة مسبقاً (لمنع تكرار إضافة النقاط)
        $existingRecord = $student->tasks()->wherePivot('task_id', $task_id)->first();

        if ($existingRecord && $existingRecord->pivot->status === 'completed') {
            return response()->json([
                'message' => 'لقد قمت بإنجاز هذه المهمة مسبقاً!',
                'already_completed' => true
            ], 200);
        }

        // 4. تسجيل المهمة كمكتملة في الجدول الوسيط
        if ($existingRecord) {
            // إذا كانت المهمة مسندة له مسبقاً (pending)، نقوم بتحديث حالتها
            $student->tasks()->updateExistingPivot($task_id, [
                'status'       => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        } else {
            // إذا لم تكن مسندة، نقوم بإنشاء سجل جديد لها كمكتملة
            $student->tasks()->attach($task_id, [
                'status'       => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        }

        // 5. إضافة نقاط المهمة إلى رصيد الطالب الإجمالي
        $student->points_balance += $task->points;
        $student->save();

        // 6. إرسال الاستجابة للـ Frontend (لتشغيل الإشعار المنبثق - Popup)
        return response()->json([
            'message'       => 'بطل يا بطل! مستوى متميز',
            'earned_points' => $task->points,          // النقاط التي كسبها للتو (مثلاً: 15+)
            'new_balance'   => $student->points_balance // الرصيد الجديد ليتم تحديثه في الواجهة العلوية
        ], 200);
    }
}
