<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $username, string $password): array
    {
        $user = User::where('username', $username)->first();

       if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['بيانات الدخول غير صحيحة.']
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user' => $user,
            'token' => $token,
        ];
    }



    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    public function forgotPassword(string $username): void
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['اسم المستخدم هذا غير مسجل لدينا في النظام!']
            ]);
        }

        DB::transaction(function () use ($username) {
            DB::table('password_resets')
                ->where('username', $username)
                ->where('status', 'pending')
                ->delete();

            DB::table('password_resets')->insert([
                'username'   => $username,
                'token'      => Str::random(60),
                'status'     => 'pending',
                'created_at' => now(),
            ]);
        });
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['كلمة المرور الحالية غير صحيحة.']
            ]);
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
