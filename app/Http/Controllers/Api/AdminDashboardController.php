<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;

class AdminDashboardController extends Controller
{
    public function index(AdminDashboardService $dashboardService)
    {
        // نستخدم __invoke لأن هذا الـ API سيكون نقطة نهاية واحدة للوحة التحكم
        return response()->json([
            'data' => $dashboardService->getDashboardData()
        ]);
    }
}
