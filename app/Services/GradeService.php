<?php

namespace App\Services;

use App\Models\Grade;

class GradeService
{
    public function createGrade(array $data): Grade
    {
        return Grade::create([
            'name'        => $data['name'],
            'level'       => $data['level'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? Grade::STATUS_ACTIVE,
        ]);
    }

    public function getAllGrades()
    {
        return Grade::orderBy('level', 'asc')->get();
    }

    public function getGradeById($id)
    {
        return Grade::findOrFail($id);
    }


    public function updateGrade($id, array $data): Grade
    {
        $grade = Grade::findOrFail($id);

        $grade->update([
            'name' => $data['name'],
            'level' => $data['level'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? $grade->status,
        ]);

        return $grade;
    }

    public function deleteGrade($id): void
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
    }

    public function getSubjectsByGradeId($id)
    {
        return Grade::findOrFail($id)->subjects()->get();
    }
}
