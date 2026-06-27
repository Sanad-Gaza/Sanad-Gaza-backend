<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(LoginRequest $request, AuthService $authService)
    {
        $result = $authService->login(
            $request->username,
            $request->password
        );

        return response()->json([
            'message' => 'Login successful',
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ]);
    }


    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'المستخدم غير مصرح له بتسجيل الخروج.'
            ], 401);
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح.'
        ]);
    }


    public function forgotPassword(ForgotPasswordRequest $request, AuthService $authService)
    {
           return $authService->forgotPassword($request->username);
    }

    public function changePassword(ChangePasswordRequest $request, AuthService $authService)
    {
        // نمرر المستخدم الحالي (request()->user()) والبيانات من الـ Request
        $authService->changePassword(
            $request->user(),
            $request->input('current_password'),
            $request->input('new_password')
        );

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح.'
        ], 200);
    }
}
