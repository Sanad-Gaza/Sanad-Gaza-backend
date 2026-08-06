<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user()->role === 'student') {
            // أضفنا 'section' لجلب تفاصيل الشعبة للطالب
            $student = $request->user()->student
                ->load('user', 'grade', 'section');
            return response()->json([
                'student' => new StudentResource($student),
            ]);
        } elseif ($request->user()->role === 'teacher') {
            // أضفنا 'sections' لجلب الشعب التي يدرسها المعلم
            $teacher = $request->user()->teacher
                ->load('user', 'subject', 'sections');
            return response()->json([
                'teacher' => new TeacherResource($teacher),
            ]);
        }
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }
}
