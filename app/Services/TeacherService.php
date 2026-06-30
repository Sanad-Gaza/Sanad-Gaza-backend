<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherService
{
    public function createTeacher(array $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'         => $data['name'],
                'username'     => $data['username'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password'     => Hash::make($data['password']),
                'role'         => 'teacher',
                'status'       => $data['status'] ?? 'active',
            ]);

            $teacher = Teacher::create([
                'user_id'        => $user->id,
                'subject_id'     => $data['subject_id'],
                'specialization' => $data['specialization'] ?? null,
                'bio'            => $data['bio'] ?? null,
            ]);

            return $teacher->load(['user', 'subject']);
        });
    }

    public function getAllTeachers()
    {
        return Teacher::with(['user', 'subject'])->get();
    }

    public function getTeacherById($id)
    {
        return Teacher::with(['user', 'subject'])->findOrFail($id);
    }

    public function updateTeacher($id, array $data): Teacher
    {
        return DB::transaction(function () use ($id, $data) {
            $teacher = Teacher::findOrFail($id);
            $user = $teacher->user;

            $userData = [
                'name'         => $data['name'],
                'username'     => $data['username'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
                'status'       => $data['status'] ?? $user->status,
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $user->update($userData);

            $teacher->update([
                'subject_id'     => $data['subject_id'],
                'specialization' => $data['specialization'] ?? $teacher->specialization,
                'bio'            => $data['bio'] ?? $teacher->bio,
            ]);

            return $teacher->load(['user', 'subject']);
        });
    }

    public function deleteTeacher($id): void
    {
        DB::transaction(function () use ($id) {
            $teacher = Teacher::findOrFail($id);
            $teacher->user->delete();
        });
    }
}
