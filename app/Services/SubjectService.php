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
}
