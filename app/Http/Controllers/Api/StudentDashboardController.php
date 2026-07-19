<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentDashboardService;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request, StudentDashboardService $dashboardService)
    {
        $data = $dashboardService->getDashboardData($request->user());

        return response()->json($data, 200);
    }
}
