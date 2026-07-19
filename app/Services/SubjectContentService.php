<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubjectContentService
{

    private function getStudentValid(User $user)
    {
        $student = $user->student;
        if (!$student) {
            throw ValidationException::withMessages([
                'student' => ['بيانات الطالب غير متوفرة']
            ]);
        }
        return $student;
    }

    public function getContent(User $user, $subjectId): array
    {
        $student = $this->getStudentValid($user);

        $subject = Subject::with('units.tasks')->findOrFail($subjectId);

        $completedTaskIds = $student->tasks()
            ->wherePivot('status', 'completed')
            ->pluck('tasks.id')
            ->toArray();

        $formattedUnits = $subject->units->map(function ($unit) use ($completedTaskIds) {
            $unitAchievedPoints = 0;

            $formattedTasks = $unit->tasks->map(function ($task) use ($completedTaskIds, &$unitAchievedPoints) {
                $isCompleted = in_array($task->id, $completedTaskIds);

                if ($isCompleted) {
                    $unitAchievedPoints += $task->points;
                }

                return [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'type'         => $task->type,
                    'url'          => $task->url,
                    'points'       => $task->points,
                    'is_completed' => $isCompleted,
                ];
            });

            return [
                'unit_id'           => $unit->id,
                'unit_title'        => $unit->title,
                'achieved_points'   => $unitAchievedPoints,
                'total_unit_points' => $unit->tasks->sum('points'),
                'tasks'             => $formattedTasks,
            ];
        });

        return [
            'subject_id'   => $subject->id,
            'subject_name' => $subject->name,
            'content'      => $formattedUnits,
        ];
    }

    public function completeTask(User $user, $taskId): array
    {
        $student = $this->getStudentValid($user);
        $task = Task::findOrFail($taskId);

        $existingRecord = $student->tasks()->wherePivot('task_id', $taskId)->first();

        if ($existingRecord && $existingRecord->pivot->status === 'completed') {
            return [
                'message'           => 'لقد قمت بإنجاز هذه المهمة مسبقاً!',
                'already_completed' => true
            ];
        }

        // استخدام Transaction لضمان أمان البيانات (النقاط + المهام + المستويات)
        return DB::transaction(function () use ($student, $task, $existingRecord) {

            // 1. تحديث حالة المهمة
            if ($existingRecord) {
                $student->tasks()->updateExistingPivot($task->id, [
                    'status'       => 'completed',
                    'completed_at' => Carbon::now(),
                ]);
            } else {
                $student->tasks()->attach($task->id, [
                    'status'       => 'completed',
                    'completed_at' => Carbon::now(),
                ]);
            }

            // 2. إضافة النقاط لرصيد الطالب
            $student->points_balance += $task->points;
            $student->save();

            // 3. التحقق من الترقية والمستويات
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

                $unitExists = $student->units()->where('unit_id', $currentUnit->id)->exists();

                if ($unitExists) {
                    $student->units()->updateExistingPivot($currentUnit->id, [
                        'status' => 'completed',
                        'stars'  => 3
                    ]);
                } else {
                    $student->units()->attach($currentUnit->id, [
                        'status' => 'completed',
                        'stars'  => 3
                    ]);
                }

                // فتح الوحدة التالية إن وجدت
                $nextUnit = Unit::where('subject_id', $currentUnit->subject_id)
                    ->where('id', '>', $currentUnit->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($nextUnit) {
                    $nextUnitExists = $student->units()->where('unit_id', $nextUnit->id)->exists();
                    if (!$nextUnitExists) {
                        $student->units()->attach($nextUnit->id, [
                            'status' => 'unlocked',
                            'stars'  => 0
                        ]);
                    }
                }
            }

            return [
                'message'           => 'بطل يا بطل! مستوى متميز',
                'earned_points'     => $task->points,
                'new_balance'       => $student->points_balance,
                'level_upgraded'    => $levelUpgraded,
                'already_completed' => false
            ];
        });
    }

    public function getSubjectMap(User $user, $subjectId): array
    {
        $student = $this->getStudentValid($user);
        $subject = Subject::with(['units.tasks'])->findOrFail($subjectId);

        $totalSubjectTasks = 0;
        $completedSubjectTasks = 0;
        $totalSubjectPoints = 0;
        $achievedSubjectPoints = 0;

        $mapData = [];

        foreach ($subject->units as $index => $unit) {
            $unitProgress = $student->units()->where('unit_id', $unit->id)->first();

            $status = 'locked';
            $stars = 0;

            if ($unitProgress) {
                $status = $unitProgress->pivot->status;
                $stars = $unitProgress->pivot->stars;
            } elseif ($index === 0) {
                // فتح أول وحدة تلقائياً إذا لم يكن هناك تقدم
                $status = 'unlocked';
                $student->units()->attach($unit->id, ['status' => 'unlocked', 'stars' => 0]);
            }

            $unitTasks = [];
            foreach ($unit->tasks as $task) {
                $totalSubjectTasks++;
                $totalSubjectPoints += $task->points;

                $isCompleted = $student->tasks()
                    ->where('task_id', $task->id)
                    ->wherePivot('status', 'completed')
                    ->exists();

                if ($isCompleted) {
                    $completedSubjectTasks++;
                    $achievedSubjectPoints += $task->points;
                }

                $unitTasks[] = [
                    'id'           => $task->id,
                    'title'        => $task->title,
                    'description'  => $task->description,
                    'type'         => $task->type,
                    'url'          => $task->url,
                    'points'       => $task->points,
                    'is_completed' => $isCompleted
                ];
            }

            $mapData[] = [
                'level_id'    => $unit->id,
                'level_title' => $unit->title,
                'status'      => $status,
                'stars'       => $stars,
                'tasks'       => $unitTasks
            ];
        }

        $progressPercentage = $totalSubjectTasks > 0 ? round(($completedSubjectTasks / $totalSubjectTasks) * 100) : 0;

        return [
            'subject_id'                  => $subject->id,
            'subject_name'                => $subject->name,
            'overall_progress_percentage' => $progressPercentage,
            'achieved_points'             => $achievedSubjectPoints,
            'total_points'                => $totalSubjectPoints,
            'levels_map'                  => $mapData
        ];
    }
}
