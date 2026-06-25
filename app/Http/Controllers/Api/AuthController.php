<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
                'message' => 'User not authenticated'
            ], 401);
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
