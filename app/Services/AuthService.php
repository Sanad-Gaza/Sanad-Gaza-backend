<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

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

    public function forgotPassword(string $username)
    {
        try {
            DB::transaction(function () use ($username) {

                $user = User::where('username', $username)->firstOrFail();

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

            return response()->json([
                'status'  => 'success',
                'message' => 'تمت العملية بنجاح، وجاري معالجة طلبك.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'اسم المستخدم هذا غير مسجل لدينا في النظام!'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ داخلي في السيرفر، يرجى المحاولة لاحقاً.',
            ], 500);
        }
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
