<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService
{
    // إنشاء طالب جديد
    public function createStudent(array $data): Student
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name'         => $data['name'],
                'username'     => $data['username'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password'     => Hash::make($data['password']),
                'role'         => User::ROLE_STUDENT, // من المودل
                'status'       => $data['status'] ?? User::STATUS_ACTIVE,
            ]);

            $student = Student::create([
                'user_id'      => $user->id,
                'grade_id'     => $data['grade_id'],
                'parent_phone' => $data['parent_phone'] ?? null,
                'gender'       => $data['gender'] ?? null,
                'birth_date'   => $data['birth_date'] ?? null,
            ]);

            // تحميل العلاقات لنقوم بعرضها في الـ Resource لاحقاً
            return $student->load(['user', 'grade']);
        });
    }

    // جلب قائمة الطلاب
    public function getAllStudents()
    {
        return Student::with(['user', 'grade'])->get();
    }
}
