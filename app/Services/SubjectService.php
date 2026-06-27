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
}
