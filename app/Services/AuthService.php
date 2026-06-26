<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class AuthService
{
    public function login(string $username, string $password): array
    {
        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            abort(response()->json([
                'message' => 'Invalid credentials'
            ], 401));
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function forgotPassword(string $username): void
    {
        DB::transaction(function () use ($username) {
            $user = User::where('username', $username)->firstOrFail();

            DB::table('password_resets')
                ->where('username', $username)
                ->where('status', 'pending')
                ->delete();

            DB::table('password_resets')->insert([
                'username' => $username,
                'token'    => Str::random(60),
                'status'   => 'pending',
                'created_at' => now(),
            ]);
        });
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('كلمة المرور الحالية غير صحيحة.');
        }

        $user->password = Hash::make($newPassword);
        $user->save();
    }
}
