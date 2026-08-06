<?php

namespace App\Services;

use App\Models\Subject;

class SubjectService
{
    public function createSubject(array $data): Subject
    {
        return Subject::create([
            'grade_id'    => $data['grade_id'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? Subject::STATUS_ACTIVE,
        ]);
    }

    public function getAllSubjects()
    {
        return Subject::with('grade')->orderBy('name', 'asc')->get();
    }

    public function getSubjectById($id)
    {
        return Subject::with('grade')->findOrFail($id);
    }

    public function updateSubject($id, array $data): Subject
    {
        $subject = Subject::findOrFail($id);

        $subject->update([
            'grade_id'    => $data['grade_id'],
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? $subject->status,
        ]);

        return $subject;
    }


    public function deleteSubject($id): void
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
    }

  public function getStudentSubjects($user)
    {
        $student = $user->student;

        if (!$student) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'student' => ['بيانات الطالب غير متوفرة']
            ]);
        }

        // 1. جلب المواد مفلترة بصف الطالب مع تحميل الوحدات والمهام المرتبطة
        $subjects = Subject::with('units.tasks')
            ->where('grade_id', $student->grade_id)
            ->where('status', Subject::STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->get();

        // 2. جلب أرقام المهام والوحدات المنجزة للطالب مرة واحدة (لتحسين الأداء)
        $completedTaskIds = $student->tasks()->wherePivot('status', 'completed')->pluck('tasks.id')->toArray();
        $unlockedUnitIds = $student->units()->pluck('units.id')->toArray();

        // 3. تشكيل البيانات وتطبيق العمليات الحسابية بدون الاعتماد على الأيقونات
        return $subjects->map(function ($subject) use ($completedTaskIds, $unlockedUnitIds) {
            $totalPoints = 0;
            $achievedPoints = 0;
            $currentLevel = 1; // المستوى الافتراضي

            foreach ($subject->units as $index => $unit) {
                // تحديد المستوى الحالي: إذا كانت الوحدة مفتوحة، يصبح مستواها هو الحالي
                if (in_array($unit->id, $unlockedUnitIds)) {
                    $currentLevel = $index + 1;
                }

                foreach ($unit->tasks as $task) {
                    $totalPoints += $task->points;

                    if (in_array($task->id, $completedTaskIds)) {
                        $achievedPoints += $task->points;
                    }
                }
            }

            // حساب النسبة المئوية
            $progressPercentage = $totalPoints > 0 ? round(($achievedPoints / $totalPoints) * 100) : 0;

            return [
                'id'                  => $subject->id,
                'name'                => $subject->name,
                'description'         => $subject->description,
                'current_level'       => $currentLevel,
                'achieved_points'     => $achievedPoints,
                'total_points'        => $totalPoints,
                'progress_percentage' => $progressPercentage,
            ];
        });
    }
}
