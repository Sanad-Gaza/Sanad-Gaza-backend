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
                'identity_number'  => $data['identity_number'],
                'first_name'       => $data['first_name'],
                'father_name'      => $data['father_name'],
                'grandfather_name' => $data['grandfather_name'],
                'family_name'      => $data['family_name'],
                'username'         => $data['username'],
                'email'            => $data['email'] ?? null,
                'phone_number'     => $data['phone_number'] ?? null,
                'password'         => Hash::make($data['password']),
                'role'             => User::ROLE_TEACHER,
                'status'           => $data['status'] ?? User::STATUS_ACTIVE,
            ]);

            $teacher = Teacher::create([
                'user_id'          => $user->id,
                'subject_id'       => $data['subject_id'],
                'gender'           => $data['gender'] ?? null,
                'birth_date'       => $data['birth_date'] ?? null,
                'qualification'    => $data['qualification'] ?? null,
                'graduation_year'  => $data['graduation_year'] ?? null,
                'specialization'   => $data['specialization'] ?? null,
                'bio'              => $data['bio'] ?? null,
            ]);

            return $teacher->load(['user', 'subject']);
        });
    }

    public function getAllTeachers($perPage = 15)
    {
        return Teacher::with(['user', 'subject'])->paginate($perPage);
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
                'identity_number'  => $data['identity_number'] ?? $user->identity_number,
                'first_name'       => $data['first_name'] ?? $user->first_name,
                'father_name'      => $data['father_name'] ?? $user->father_name,
                'grandfather_name' => $data['grandfather_name'] ?? $user->grandfather_name,
                'family_name'      => $data['family_name'] ?? $user->family_name,
                'username'         => $data['username'] ?? $user->username,
                'email'            => $data['email'] ?? null,
                'phone_number'     => $data['phone_number'] ?? $user->phone_number,
                'status'           => $data['status'] ?? $user->status,
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
