<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure
     * @param  string  $role 
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالدخول، يرجى تسجيل الدخول أولاً وتمرير التوكن (Token).'
            ], 401);
        }

        if ($user->role !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، صلاحياتك كـ (' . $user->role . ') لا تسمح لك بتنفيذ هذا الإجراء.'
            ], 403);
        }

        return $next($request);
    }
}
