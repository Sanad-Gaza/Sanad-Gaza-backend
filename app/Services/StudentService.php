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
                'identity_number'  => $data['identity_number'],
                'first_name'       => $data['first_name'],
                'father_name'      => $data['father_name'],
                'grandfather_name' => $data['grandfather_name'],
                'family_name'      => $data['family_name'],
                'username'         => $data['username'],
                'email'            => $data['email'] ?? null,
                'phone_number'     => $data['phone_number'] ?? null,
                'password'         => Hash::make($data['password']),
                'role'             => User::ROLE_STUDENT,
                'status'           => $data['status'] ?? User::STATUS_ACTIVE,
            ]);

            $student = Student::create([
                'user_id'       => $user->id,
                'grade_id'      => $data['grade_id'],
                'section'       => $data['section'] ?? null,
                'health_status' => $data['health_status'] ?? null,
                'gender'        => $data['gender'] ?? null,
                'birth_date'    => $data['birth_date'] ?? null,
            ]);

            return $student->load(['user', 'grade']);
        });
    }

    public function getAllStudents()
    {
        return Student::with(['user', 'grade'])->get();
    }



    public function getStudentById($id)
    {
        return Student::with(['user', 'grade'])->findOrFail($id);
    }

    public function updateStudent($id, array $data): Student
    {
        return DB::transaction(function () use ($id, $data) {
            $student = Student::findOrFail($id);
            $user = $student->user;

            $userData = [
                'identity_number'  => $data['identity_number'],
                'first_name'       => $data['first_name'],
                'father_name'      => $data['father_name'],
                'grandfather_name' => $data['grandfather_name'],
                'family_name'      => $data['family_name'],
                'username'         => $data['username'],
                'email'            => $data['email'],
                'status'           => $data['status'] ?? $user->status,
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }
            if (isset($data['phone_number'])) {
                $userData['phone_number'] = $data['phone_number'];
            }

            $user->update($userData);

            $student->update([
                'grade_id'      => $data['grade_id'],
                'section'       => $data['section'] ?? $student->section,
                'health_status' => $data['health_status'] ?? $student->health_status,
                'parent_phone'  => $data['parent_phone'] ?? $student->parent_phone,
                'gender'        => $data['gender'] ?? $student->gender,
                'birth_date'    => $data['birth_date'] ?? $student->birth_date,
            ]);

            return $student->load(['user', 'grade']);
        });
    }

    // حذف طالب
    public function deleteStudent($id)
    {
        return DB::transaction(function () use ($id) {
            $student = Student::findOrFail($id);
            $student->user->delete();
            return true;
        });
    }

    //Student Full Name
    public function getStudentFullName($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $user = $student->user;
        return "{$user->first_name} {$user->father_name} {$user->grandfather_name} {$user->family_name}";
    }
}
