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
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->username,
            $request->password
        );

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token'   => $result['token'],
            'user'    => new UserResource($result['user']),
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح.'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->authService->forgotPassword($request->username);

        return response()->json([
            'message' => 'تمت العملية بنجاح، وجاري معالجة طلبك.'
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword(
            $request->user(),
            $request->input('current_password'),
            $request->input('new_password')
        );

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح.'
        ], 200);
    }
}
